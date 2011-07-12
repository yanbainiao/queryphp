Validator = Class.create();

Validator.prototype = {
	initialize : function(className, error, test, options) {
		if(typeof test == 'function'){
			this.options = $H(options);
			this._test = test;
		} else {
			this.options = $H(test);
			this._test = function(){return true};
		}
		this.error = error || 'Validation failed.';
		this.className = className;
	},
	test : function(v, elm) {
		return (this._test(v,elm) && this.options.all(function(p){
			return Validator.methods[p.key] ? Validator.methods[p.key](v,elm,p.value) : true;
		}));
	}
}
Validator.methods = {
	pattern : function(v,elm,opt) {return Validation.get('IsEmpty').test(v) || opt.test(v)},
	minLength : function(v,elm,opt) {return v.length >= opt},
	maxLength : function(v,elm,opt) {return v.length <= opt},
	min : function(v,elm,opt) {return v >= parseFloat(opt)}, 
	max : function(v,elm,opt) {return v <= parseFloat(opt)},
	notOneOf : function(v,elm,opt) {return $A(opt).all(function(value) {
		return v != value;
	})},
	oneOf : function(v,elm,opt) {return $A(opt).any(function(value) {
		return v == value;
	})},
	is : function(v,elm,opt) {return v == opt},
	isNot : function(v,elm,opt) {return v != opt},
	equalToField : function(v,elm,opt) {return v == $F(opt)},
	notEqualToField : function(v,elm,opt) {return v != $F(opt)},
	include : function(v,elm,opt) {return $A(opt).all(function(value) {
		return Validation.get(value).test(v,elm);
	})}
}

var Validation = Class.create();

Validation.prototype = {
	initialize : function(form, options){
		this.options = Object.extend({
			onSubmit : true,
			stopOnFirst : false,
			immediate : false,
			focusOnError : true,
			useTitles : false,
			onFormValidate : function(result, form) {},
			onElementValidate : function(result, elm) {}
		}, options || {});
		this.form = $(form);
		if(this.options.onSubmit) Event.observe(this.form,'submit',this.onSubmit.bind(this),false);
		if(this.options.immediate) {
			var useTitles = this.options.useTitles;
			var callback = this.options.onElementValidate;
			Form.getElements(this.form).each(function(input) { // Thanks Mike!
				Event.observe(input, 'blur', function(ev) { Validation.validate(Event.element(ev),{useTitle : useTitles, onElementValidate : callback}); });
			});
		}
	},
	onSubmit :  function(ev){
		if(!this.validate()) Event.stop(ev);
	},
	validate : function() {
		var result = false;
		var useTitles = this.options.useTitles;
		var callback = this.options.onElementValidate;
		if(this.options.stopOnFirst) {
			result = Form.getElements(this.form).all(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); });
		} else {
			result = Form.getElements(this.form).collect(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); }).all();
		}
		if(!result && this.options.focusOnError) {
			Form.getElements(this.form).findAll(function(elm){return $(elm).hasClassName('validation-failed')}).first().focus()
		}
		if(!this.options.onFormValidate(result, this.form))
		{
		  return result;
		}else{
		  return false;
		}
	},
	reset : function() {
		Form.getElements(this.form).each(Validation.reset);
	}
}

Object.extend(Validation, {
	validate : function(elm, options){
		options = Object.extend({
			useTitle : false,
			onElementValidate : function(result, elm) {}
		}, options || {});
		elm = $(elm);
		var cn = elm.classNames();
		return result = cn.all(function(value) {
			var test = Validation.test(value,elm,options.useTitle);
			options.onElementValidate(test, elm);
			return test;
		});
	},
	_getInputValue : function(elm) {
		var elm = $(elm);
		if(elm.type.toLowerCase() == 'file') {
			return elm.value;
		}else {
			return $F(elm);
		}
	},
	_getErrorMsg : function(useTitle,elm,error) {
		if( typeof(error) == 'function' ) {
			error = error(Validation._getInputValue(elm),elm);
		}
		return useTitle ? ((elm && elm.title) ? elm.title : error) : error;
	},
	test : function(name, elm, useTitle) {
		var v = Validation.get(name);
		var prop = '__advice'+name.camelize();
		try {
		if(Validation.isVisible(elm) && !v.test($F(elm), elm)) {
			if(!elm[prop]) {
				var advice = Validation.getAdvice(name, elm);
				if(advice == null) {
					var errorMsg = elm.title ? ((elm && elm.title) ? elm.title : v.error) : v.error;
					advice = '<div class="validation-advice" id="advice-' + name + '-' + Validation.getElmID(elm) +'" style="display:none">' + errorMsg + '</div>'
					switch (elm.type.toLowerCase()) {
						case 'checkbox':
						case 'radio':
							var p = elm.parentNode;
							if(p) {
								new Insertion.Bottom(p, advice);
							} else {
								new Insertion.After(elm, advice);
							}
							break;
						default:
							new Insertion.After(elm, advice);
				    }
					advice = Validation.getAdvice(name, elm);
				}
				Element.show(advice);
			}
			elm[prop] = true;
			elm.removeClassName('validation-passed');
			elm.addClassName('validation-failed');
			return false;
		} else {
			var advice = Validation.getAdvice(name, elm);
			if(advice != null) advice.hide();
			elm[prop] = '';
			elm.removeClassName('validation-failed');
			elm.addClassName('validation-passed');
			return true;
		}
		} catch(e) {
			throw(e)
		}
	},
	isVisible : function(elm) {
		while(elm && elm.tagName != 'BODY') {
			if(!$(elm).visible()) return false;
			elm = elm.parentNode;
		}
		return true;
	},
	getAdvice : function(name, elm) {
		return $('advice-' + name + '-' + Validation.getElmID(elm)) || $('advice-' + Validation.getElmID(elm));
	},
	getElmID : function(elm) {
		return elm.id ? elm.id : elm.name;
	},
	reset : function(elm) {
		elm = $(elm);
		var cn = elm.classNames();
		cn.each(function(value) {
			var prop = '__advice'+value.camelize();
			if(elm[prop]) {
				var advice = Validation.getAdvice(value, elm);
				advice.hide();
				elm[prop] = '';
			}
			elm.removeClassName('validation-failed');
			elm.removeClassName('validation-passed');
		});
	},
	add : function(className, error, test, options) {
		var nv = {};
		nv[className] = new Validator(className, error, test, options);
		Object.extend(Validation.methods, nv);
	},
	addAllThese : function(validators) {
		var nv = {};
		$A(validators).each(function(value) {
				nv[value[0]] = new Validator(value[0], value[1], value[2], (value.length > 3 ? value[3] : {}));
			});
		Object.extend(Validation.methods, nv);
	},
	get : function(name) {
		return  Validation.methods[name] ? Validation.methods[name] : Validation.methods['_LikeNoIDIEverSaw_'];
	},
   methods : {
		'_LikeNoIDIEverSaw_' : new Validator('_LikeNoIDIEverSaw_','',{})
	}

});

Validation.add('IsEmpty', '', function(v) {
				return  ((v == null) || (v.length == 0)); // || /^\s+$/.test(v));
			});

Validation.addAllThese([
	['required', '这里不能为空.', function(v) {
				return !Validation.get('IsEmpty').test(v);
			}],
	['validate-number', '请输入正确的数字', function(v) {
				return Validation.get('IsEmpty').test(v) || (!isNaN(v) && !/^\s+$/.test(v));
			}],
	['validate-digits', '请输入一个数字. 避免输入空格,逗号,分号等字符', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/[^\d]/.test(v);
			}],
	['validate-alpha', '请输入[a-z]的字母', function (v) {
				return Validation.get('IsEmpty').test(v) ||  /^[a-zA-Z]+$/.test(v)
			}],
	['validate-alphanum', '请输入[a-z]的字母或是[0-9]的数字,其它字符是不允许的.', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/\W/.test(v)
			}],
	['validate-date', '请输入有效的日期', function(v) {
				var test = new Date(v);
				return Validation.get('IsEmpty').test(v) || !isNaN(test);
			}],
	['validate-email', '请输入有效的邮件地址,如 username@example.com .', function (v) {
				return Validation.get('IsEmpty').test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)
			}],
	['validate-url', '请输入有效的URL地址.', function (v) {
				return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(v)
			}],
	['validate-date-au', 'Please use this date format: dd/mm/yyyy. For example 17/03/2006 for the 17th of March, 2006.', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				return ( parseInt(RegExp.$2, 10) == (1+d.getMonth()) ) && 
							(parseInt(RegExp.$1, 10) == d.getDate()) && 
							(parseInt(RegExp.$3, 10) == d.getFullYear() );
			}],
	['validate-currency-dollar', 'Please enter a valid $ amount. For example $100.00 .', function(v) {
				// [$]1[##][,###]+[.##]
				// [$]1###+[.##]
				// [$]0.##
				// [$].##
				return Validation.get('IsEmpty').test(v) ||  /^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(v)
			}],
	['validate-one-check', '在上面选项至少选择一个.', function (v,elm) {
				var p = elm.parentNode.parentNode.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return $A(options).any(function(elm) {
					return $F(elm);
				});
			}],
	['validate-one-required', '在上面选项至少选择一个.', function (v,elm) {
				var p = elm.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return $A(options).any(function(elm) {
					return $F(elm);
				});
			}],
    ['validate-chinese','必需是中文',function (v,elm){
	          
			    return Validation.get('IsEmpty').test(v)||/^[\u4e00-\u9fa5]+$/.test(v);
			    
				 
			}],
    ['validate-confirm','不能为空，必需前后相同',function (v,elm){
	      
			    if(!Validation.get('IsEmpty').test(v))
				{
				   if(!($(elm.id+'_confirm')))
				   {
				     return false;
				   }
				   if(elm.value==$(elm.id+'_confirm').value)
				   {	
				      return true;
				   }else{
					     return false;
				   }
				}else{
				  return false;
				}
			    return Validation.get('IsEmpty').test(v)||/^[\u4e00-\u9fa5]+$/.test(v);
			    
				 
			}],
    ['validate-phone','有效的手机号',function (v,elm){	          
			    return Validation.get('IsEmpty').test(v)||/^(0?[13|15])+\d{9}$/.test(v);	    
				 
			}],
    ['validate-iphone','有效的电话号',function (v,elm){	          
			    return Validation.get('IsEmpty').test(v)||/^[0]{1}[0-9]{2,3}([-|\s])?[0-9]{7,8}$/.test(v);	    
				 
			}],
    ['validate-zip','真入正确的邮政编码',function (v,elm){	        
		         
			    return Validation.get('IsEmpty').test(v)||/^[0-9]{6}$/.test(v);	    
				 
			}],			
    ['validate-htmlname','真入正确的内容',function (v,elm){	        
		         
			    return Validation.get('IsEmpty').test(v)||/^[a-zA-Z0-9-_,@]+$/.test(v);	    
				 
			}],
    ['validate-qq','请输入正确的QQ号',function (v,elm){	          
			    return Validation.get('IsEmpty').test(v)||/^[1-9]\d{4,9}$/.test(v);	    
				 
			}]
]);

//ValidationFactory for cache
var ValidationFactory = function(){};
ValidationFactory._cacheValidation = {};
ValidationFactory.create = function(form,options) {
	var inCacheValidation = ValidationFactory._cacheValidation[form];
	if(inCacheValidation)
		return  inCacheValidation;
	var validation = new Validation(form,options);
	ValidationFactory._cacheValidation[form] = validation;
	return validation;
}

//custom validate start
Validation.addAllThese([
	['validate-date-cn', '请使用这样的日期格式: yyyy-mm-dd. 例如:2006-03-17.', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^(\d{4})-(\d{2})-(\d{2})$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$1/$2/$3'));
				return ( parseInt(RegExp.$2, 10) == (1+d.getMonth()) ) && 
							(parseInt(RegExp.$3, 10) == d.getDate()) && 
							(parseInt(RegExp.$1, 10) == d.getFullYear() );
			}]
]);
/*
 * Usage: min-length-number
 * Example: min-length-10
 */
Validation.add(
	'min-length', 
	function(v,elm) {
		var results = elm.className.match(/min-length-(\d*)/);
		var minLength = parseInt(results[1]);
		return '最小长度为'+minLength
	},
	function(v,elm) {
		var results = elm.className.match(/min-length-(\d*)/);
		var minLength = parseInt(results[1]);
		return Validation.get('IsEmpty').test(v) || v.length >= minLength
	}
)
/*
 * Usage: max-length-number
 * Example: max-length-10
 */
Validation.add(
	'max-length', 
	function(v,elm) {
		var results = elm.className.match(/max-length-(\d*)/);
		var maxLength = parseInt(results[1]);
		return '最大长度为'+maxLength
	},
	function(v,elm) {
		var results = elm.className.match(/max-length-(\d*)/);
		var maxLength = parseInt(results[1]);
		return Validation.get('IsEmpty').test(v) || v.length <= maxLength
	}
)
/*
 * Usage: validate-file-type1-type2-typeX
 * Example: validate-file-png-jpg-jpeg
 */
Validation.add(
	'validate-file', 
	function(v,elm) {
		var results = elm.className.match(/validate-file-([a-zA-Z0-9-]*)/);
		var extentionNamesStr = results[1];
		var extentionNames = extentionNamesStr.split('-');
		return '文件类型应该为'+extentionNames.join(',');
	},
	function(v,elm) {
		var results = elm.className.match(/validate-file-([a-zA-Z0-9-]*)/);
		var extentionNamesStr = results[1];
		var extentionNames = extentionNamesStr.split('-');
		return Validation.get('IsEmpty').test(v) || extentionNames.any(function(extentionName) {
			var pattern = new RegExp('\\.'+extentionName+'$','i');
			return pattern.test(v);
		});
	}
)

/*
 * Usage: validate-float-range-minValue-maxValue
 * Example: -2.1 to 3 = validate-float-range--2.1-3
 */
Validation.add(
	'validate-float-range', 
	function(v,elm) {
		if(!Validation.get('validate-number').test(v)) {
			return Validation.get('validate-number').error;
		}
		var results = elm.className.match(/validate-float-range-(-?[\d\.]*)-(-?[\d\.]*)/);
		var minValue = parseFloat(results[1]);
		var maxValue = parseFloat(results[2]);
		return '输入值应该为'+minValue+" 至 "+maxValue+"之间";
	},
	function(v,elm) {
		var results = elm.className.match(/validate-float-range-(-?[\d\.]*)-(-?[\d\.]*)/);
		var minValue = parseFloat(results[1]);
		var maxValue = parseFloat(results[2]);
		return Validation.get('IsEmpty').test(v) || (Validation.get('validate-number').test(v) && (parseFloat(v) >= minValue && parseFloat(v) <= maxValue))
	}
)

/*
 * Usage: validate-int-range-minValue-maxValue
 * Example: -10 to 20 = validate-int-range--10-20
 */
Validation.add(
	'validate-int-range', 
	function(v,elm) {
		if(!Validation.get('validate-number').test(v)) {
			return Validation.get('validate-number').error;
		}
		var results = elm.className.match(/validate-int-range-(-?\d*)-(-?\d*)/);
		var minValue = parseInt(results[1]);
		var maxValue = parseInt(results[2]);
		return '输入值应该为'+minValue+' 至 '+maxValue+'之间的整数';
	},
	function(v,elm) {
		var results = elm.className.match(/validate-int-range-(-?\d*)-(-?\d*)/);
		var minValue = parseInt(results[1]);
		var maxValue = parseInt(results[2]);
		return Validation.get('IsEmpty').test(v) || (Validation.get('validate-number').test(v) && (parseInt(v) >= minValue && parseInt(v) <= maxValue))
	} 
)

/*
 * Usage: validate-length-range-minLength-maxLength
 * Example: 10 to 20 = validate-length-range-10-20
 */
Validation.add(
	'validate-length-range', 
	function(v,elm) {
		var results = elm.className.match(/validate-length-range-(\d*)-(\d*)/);
		var minLength = parseInt(results[1]);
		var maxLength = parseInt(results[2]);
		return '长度应该在'+minLength+' - '+maxLength+'之间';
	},
	function(v,elm) {
		var results = elm.className.match(/validate-length-range-(\d*)-(\d*)/);
		var minLength = parseInt(results[1]);
		var maxLength = parseInt(results[2]);
		return Validation.get('IsEmpty').test(v) || (v.length >= minLength && v.length <= maxLength)
	}
)

/*
 * Usage: max-value-number
 * Example: max-value-10
 */
Validation.add(
	'max-value', 
	function(v,elm) {
		if(!Validation.get('validate-number').test(v)) {
			return Validation.get('validate-number').error;
		}
		var results = elm.className.match(/max-value-(-?[\d\.]*)/);
		var value = parseFloat(results[1]);
		return '最大值为'+value;
	},
	function(v,elm) {
		var results = elm.className.match(/max-value-(-?[\d\.]*)/);
		var value = parseFloat(results[1]);
		return Validation.get('IsEmpty').test(v) || (Validation.get('validate-number').test(v) && parseFloat(v) <= value);
	}
)

/*
 * Usage: min-value-number
 * Example: min-value-10
 */
Validation.add(
	'min-value', 
	function(v,elm) {
		if(!Validation.get('validate-number').test(v)) {
			return Validation.get('validate-number').error;
		}
		var results = elm.className.match(/min-value-(-?[\d\.]*)/);
		var value = parseFloat(results[1]);
		return '最小值为'+value;
	},
	function(v,elm) {
		var results = elm.className.match(/min-value-(-?[\d\.]*)/);
		var value = parseFloat(results[1]);
		return Validation.get('IsEmpty').test(v) || (Validation.get('validate-number').test(v) && parseFloat(v) >= value);
	}
)

/*
 * Usage: validate-equals-item1-item2-itemX
 * Example: validate-equals-AA-BB-CC
 */
Validation.add(
	'validate-equals', 
	function(v,elm) {
		var results = elm.className.match(/validate-equals-([\S]*)/);
		var expectedValuesStr = results[1];
		var expectedValues = expectedValuesStr.split('-');
		return '期待的值应该为其中['+expectedValues.join(',')+"]之一";
	},
	function(v,elm) {
		var results = elm.className.match(/validate-equals-([\S]*)/);
		var expectedValuesStr = results[1];
		var expectedValues = expectedValuesStr.split('-');
		return Validation.get('IsEmpty').test(v) || expectedValues.any(function(expectedValue) {
			return v == expectedValue;
		});
	}
)