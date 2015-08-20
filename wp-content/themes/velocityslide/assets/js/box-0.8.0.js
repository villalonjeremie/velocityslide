var box = {
    modules: {},
     require: function(id) {
         var modules = box.modules;
         return id in modules ? modules[id].exports : null;
     },

     define: function(id, definition) {
         if(typeof id !== 'string' || !id) {
         throw new Error('invalid module id ("' + id + '")');
     }

     if(id in box.modules) {
         throw new Error('module with id "' + id + '" is already defined');
     }

     var module = { id: id };
     if(typeof definition === 'function') {
         definition(box.require, module, box.define);
     } else {
         module.exports = definition;
     }

     box.modules[id] = module;
     return module.exports;
     }
};

 box.define('jquery', function(require, module) {
     module.exports = jQuery;
 });

 box.define('lang/kindOf', function(require, module) {
     var objToString = Object.prototype.toString;
     var reType = /^\[object ([a-z]+)\]$/;
     var undefType = 'undefined';

     module.exports = function(o) {

     if(o === null) {
        return 'null';
     }
     if(typeof o === undefType) {
        return undefType;
     }
     return objToString.call(o).toLowerCase().match(reType)[1];
     };
     });

     box.define('lang/isNull', function(require, module) {
         var kindOf = require('lang/kindOf');
         module.exports = function(o) {
         return kindOf(o) === 'null';
     };
 });

 box.define('lang/isUndefined', function(require, module) {
 var kindOf = require('lang/kindOf');

 module.exports = function(o) {
 return kindOf(o) === 'undefined';
 };
 });

 box.define('lang/isObject', function(require, module) {
 var kindOf = require('lang/kindOf');

 module.exports = function(o) {
 return kindOf(o) === 'object';
 };
 });

 box.define('lang/isArray', function(require, module) {
 var kindOf = require('lang/kindOf');

 if(Array.isArray) {
 module.exports = function(o) {
 return Array.isArray(o);
 };
 } else {
 module.exports = function(o) {
 return kindOf(o) === 'array';
 };
 }
 });

 box.define('lang/types', function(require, module) {
 module.exports = {
 isArray: require('lang/isArray'),
 isNull: require('lang/isNull'),
 isObject: require('lang/isObject'),
 isUndefined: require('lang/isUndefined'),
 kindOf: require('lang/kindOf')
 };
 });

 box.define('array/indexOf', function(require, module) {
 module.exports = function(array, item) {
 var i = -1;
 var l = array.length;
 while(++i < l) {
 if(array[i] === item) {
 return i;
 }
 }
 return -1;
 };
 });

 box.define('array/remove', function(require, module) {
 var indexOf = require('array/indexOf');

 module.exports = function(array, item) {
 var index = indexOf(array, item);
 if(index > -1) {
 array.splice(index, 1);
 }
 };
 });

 box.define('object/mixin', function(require, module) {
 module.exports = function(target, source) {
 for(var name in source) {
 if(source.hasOwnProperty(name)) {
 target[name] = source[name];
 }
 }
 return target;
 };
 });

 box.define('object/defaults', function(require, module) {
 var kindOf = require('lang/kindOf');
 var reMissing = /^null|undefined$/;

 module.exports = function(target) {
 var i = 0;
 var l = arguments.length;
 var src, name, kind;
 while(++i < l) {
 src = arguments[i];
 for(name in src) {
 if(src.hasOwnProperty(name)) {
 kind = kindOf(target[name]);
 if(reMissing.test(kind)) {
 target[name] = src[name];
 }
 }
 }
 }
 return target;
 };
 });

 box.define('function/bind', function(require, module) {
 module.exports = function(fn, context) {
 return function() {
 return fn.apply(context, arguments);
 };
 };
 });

 box.define('function/debounce', function(require, module) {
 module.exports = function(fn, delay, isImmediate) {
 var timeoutId, args, context, result;

 function delayed() {
 if(!isImmediate) {
 result = fn.apply(context, args);
 }
 timeoutId = null;
 }

 function debounced() {
 args = arguments;
 context = this;
 if(timeoutId) {
 clearTimeout(timeoutId);
 } else if(isImmediate) {
 result = fn.apply(context, args);
 }
 timeoutId = setTimeout(delayed, delay);
 return result;
 }

 return debounced;
 };
 });

 box.define('component/execModule', function(require, module) {
 module.exports = {
 _exec: function(type, id, args) {
 var parts = id.split('@');
 var target = require(parts[0]);
 if(parts[1]) {
 if(target.Component) {
 return target.Component.prototype[parts[1]][type](this, args);
 }
 return target[parts[1]][type](this, args);
 }
 return target[type](this, args);
 },

 callModule: function(id, arg) {
 return this._exec('call', id, arg);
 },

 applyModule: function(id) {
 return this._exec('apply', id, Array.prototype.slice.call(arguments, 1));
 }
 };
 });

 box.define('component/factory', function(require, module) {
 function Factory(source) {
 this._prepareComponent(source);
 }

 var protoFactory = Factory.prototype;
 var isArray = require('lang/isArray');
 var isObject = require('lang/isObject');
 var mixin = require('object/mixin');
 var defaults = require('object/defaults');

 protoFactory._prepareComponent = function(source) {
 function Component() {}

 this.Component = Component;
 var proto = Component.prototype;
 var defaults = {};
 this._addMixinsToProto(proto, defaults, source.MIXINS);
 this._mixinToProto(proto, defaults, source);
 this._mixinObjectToProto(proto, defaults, 'component/execModule');
 proto.DEFAULTS = defaults;
 };

 protoFactory._addMixinsToProto = function(proto, defaults, mixins) {
 if(isArray(mixins)) {
 var i = -1;
 var l = mixins.length;
 while(++i < l) {
 this._mixinObjectToProto(proto, defaults, mixins[i]);
 }
 }
 };

 protoFactory._mixinObjectToProto = function(proto, defaults, id) {
 var ext = require(id);
 if(!isObject(ext)) {
 throw new Error('component cannot mixin non-object module "' + id + '"');
 }
 if(typeof ext.Component === 'function') {
 ext = ext.Component.prototype;
 }
 this._mixinToProto(proto, defaults, ext);
 };

 protoFactory._mixinToProto = function(proto, defaults, from) {
 for(var name in from) {
 if(from.hasOwnProperty(name)) {
 if(name === 'DEFAULTS') {
 mixin(defaults, from[name]);
 } else {
 proto[name] = from[name];
 }
 }
 }
 };

 protoFactory.create = function(cfg) {
 var obj = new this.Component();
 if(!isObject(cfg)) {
 cfg = {};
 }
 if(isObject(obj.DEFAULTS)) {
 defaults(cfg, obj.DEFAULTS);
 }
 obj.cfg = cfg;
 if(typeof obj.setup === 'function' && cfg.setupImmediatly !== false) {
 obj.setup(cfg);
 }
 return obj;
 };

 module.exports = {
 Factory: Factory,

 create: function(source) {
 if(!isObject(source)) {
 throw new Error('missing prototype for component creation');
 }
 return new Factory(source);
 }
 };
 });

 box.define('events/Event', function(require, module) {
 function Event(type, source, details) {
 if(typeof type !== 'string' || !this.RE_EVENT_TYPE.test(type)) {
 throw new Error('"' + type + '" is not a valid type for Event');
 }
 if(!source) {
 throw new Error('cannot create an event "' + type + '" without a source');
 }
 this.type = type;
 this.source = source;
 this.details = details || {};
 this.isDefaultPrevented = false;
 this.isPropagationStopped = false;
 }

 var proto = Event.prototype;

 proto.RE_EVENT_TYPE = /^[a-z]+$/i;

 proto.preventDefault = function() {
 this.isDefaultPrevented = true;
 };

 proto.stopPropagation = function() {
 this.isPropagationStopped = true;
 };

 module.exports = Event;
 });

 box.define('events/listeners', function(require, module) {
 var Event = require('events/Event');
 var isObject = require('lang/isObject');

 module.exports = require('component/factory').create({
 setup: function() {
 this.data = {};
 },

 add: function(type, listener) {
 if(typeof type !== 'string' || !Event.prototype.RE_EVENT_TYPE.test(type)) {
 throw new Error('cannot add listener with the invalid "' + type + '" event type');
 }
 var isListenerFunction = typeof listener === 'function';
 var isListenerObject = !isListenerFunction && isObject(listener);
 var method, namespace, additional, created;
 if(!isListenerFunction && !isListenerObject) {
 throw new Error('invalid listener for the "' + type + '" event type');
 }
 if(isListenerObject) {
 method = arguments[2];
 namespace = arguments[3];
 if(typeof method !== 'string') {
 throw new Error('no method name given on the object listener for the "' + type + '" event type');
 }
 additional = arguments[4];
 } else {
 namespace = arguments[2];
 additional = arguments[3];
 }
 created = {
 listener: listener,
 method: method,
 namespace: namespace,
 additional: additional
 };
 if(type in this.data) {
 this.data[type].push(created);
 } else {
 this.data[type] = [ created ];
 }
 return this;
 },

 remove: function(type, namespace) {
 if(typeof type !== 'string' || !Event.prototype.RE_EVENT_TYPE.test(type)) {
 if(type !== '*') {
 throw new Error('cannot remove listener with the invalid "' + type + '" event type');
 }
 }
 var allListeners = this.data;
 if(type === '*') {
 if(namespace) {
 for(type in allListeners) {
 if(allListeners.hasOwnProperty(type)) {
 this._removeByNamespace(type, namespace);
 }
 }
 } else {
 this.data = {};
 }
 } else if(type in allListeners) {
 if(namespace) {
 this._removeByNamespace(type, namespace);
 } else {
 allListeners[type] = [];
 }
 }
 return this;
 },

 _removeByNamespace: function(type, namespace) {
 var typedListeners = this.data[type];
 var i = typedListeners.length;
 while(i--) {
 if(typedListeners[i].namespace === namespace) {
 typedListeners.splice(i, 1);
 }
 }
 },

 execute: function(type, evt) {
 var allListeners = this.data;
 var typedListeners, i, l, current;
 if(type in allListeners) {
 typedListeners = allListeners[type];
 i = -1;
 l = typedListeners.length;
 while(++i < l) {
 current = typedListeners[i];
 if(typeof current.method === 'string') {
 current.listener[current.method](evt, current.additional);
 } else {
 current.listener(evt, current.additional);
 }
 if(evt.isPropagationStopped) {
 break;
 }
 }
 }
 }
 });
 });

 box.define('events/target', function(require, module) {
 var Event = require('events/Event');
 var listeners = require('events/listeners');

 module.exports = require('component/factory').create({
 setup: function(cfg) {
 this.listeners = cfg && cfg.listeners instanceof listeners.Component ? cfg.listeners : listeners.create();
 },

 on: function(type, listener, mixed1, mixed2, mixed3) {
 this.listeners.add(type, listener, mixed1, mixed2, mixed3);
 return this;
 },

 off: function(type, namespace) {
 this.listeners.remove(type, namespace);
 return this;
 },

 dispatch: function(type, details) {
 if(typeof type !== 'string' || !Event.prototype.RE_EVENT_TYPE.test(type)) {
 throw new Error('cannot dispatch the invalid "' + type + '" event type');
 }
 var evt = new Event(type, this, details);
 this.listeners.execute(type, evt);
 return evt;
 }
 });
 });

 box.define('sequence/step', function(require, module) {
 var sequenceStep = module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 setup: function(name, definition) {
 this.name = name;
 if(typeof definition.enter === 'function') {
 this.enter = definition.enter;
 }
 this.main = definition.main;
 this.leave = definition.leave;
 this.isReady = true;
 this.isEntering = false;
 this.calls = 0;
 this.callModule('events/target@setup');
 },

 play: function(data) {
 if(this.isReady) {
 this.isReady = false;
 this._data = data;
 if(this.enter) {
 this.proceedEnter();
 } else {
 this.proceedMain();
 }
 }
 },

 when: function(obj) {
 if(!obj || typeof obj.on !== 'function' || typeof obj.off !== 'function') {
 throw Error('cannot listen an event on the given object for step "' + this.name + '"');
 }
 this._when = obj;
 return this;
 },

 emit: function(evtType) {
 if(!evtType || typeof evtType !== 'string') {
 throw new Error('invalid event type for step "' + this.name + '"');
 }
 this._emit = evtType;
 return this;
 },

 wait: function(delay) {
 if(isNaN(delay) || delay < 0) {
 throw new Error('invalid step delay for "' + this.name + '"');
 }
 this._wait = delay;
 return this;
 },

 proceed: function(name) {
 if(!name || typeof name !== 'string') {
 throw new Error('invalid proceed name for step "' + this.name + '"');
 }
 this.proceedCalled = true;
 if(this._when && this._emit) {
 this.proceedWhen(name);
 } else if(this._wait) {
 this.proceedWait(name);
 } else {
 var lname = name.toLowerCase();
 if(lname === 'main') {
 this.proceedMain();
 } else if(lname === 'wait') {
 this.isReady = true;
 } else {
 if(this.isEntering || lname === 'exit') {
 this.isReady = true;
 this.isEntering = false;
 }
 this.dispatch('proceed', { target: lname === 'exit' ? lname : name });
 }
 }
 },

 proceedEnter: function() {
 this.isEntering = true;
 this.proceedCalled = false;
 this.enter(this._data);
 if(!this.proceedCalled) {
 throw new Error('no call to proceed in "enter" for step "' + this.name + '"');
 }
 },

 proceedMain: function() {
 this.isEntering = false;
 this.main(this._data);
 this.calls++;
 this.isReady = true;
 this.proceedLeave();
 },

 proceedLeave: function() {
 if(typeof this.leave === 'function') {
 this.proceedCalled = false;
 this.leave(this._data);
 if(!this.proceedCalled) {
 throw new Error('no call to proceed in "leave" for step "' + this.name + '"');
 }
 } else {
 this.proceed(this.leave);
 }
 },

 proceedWhen: function(name) {
 this._when.on(this._emit, this, 'proceedWhenListener', this, name);
 this._when = this._emit = null;
 },

 proceedWhenListener: function(evt, name) {
 this.dispatch('relay', evt);
 this.dispatch('proceed', { target: name });
 evt.source.off(evt.type, this);
 },

 proceedWait: function(name) {
 var bind = require('function/bind');
 setTimeout(bind(function() {
 this.dispatch('proceed', { target: name });
 }, this), this._wait);
 this._wait = null;
 }
 });

 sequenceStep.RE_INVALID_NAME = /^exit|main|wait$/i;

 sequenceStep.create = function(name, definition) {
 if(!name || typeof name !== 'string' || this.RE_INVALID_NAME.test(name)) {
 throw new Error('invalid step name "' + name + '"');
 }
 if(!definition || typeof definition.main !== 'function' || !definition.leave || (typeof definition.leave !== 'function' && typeof definition.leave !== 'string')) {
 throw new Error('invalid step definition for "' + name + '"');
 }
 var step = new this.Component();
 step.setup(name, definition);
 return step;
 };
 });

 box.define('sequence/manager', function(require, module) {
 var step = require('sequence/step');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 isFreezed: false,
 isPlaying: false
 },

 setup: function(cfg) {
 if(!cfg.name || typeof cfg.name !== 'string') {
 throw new Error('missing or invalid sequence name');
 }
 this.steps = {};
 this.stepsCount = 0;
 this.callModule('events/target@setup', cfg);
 },

 describe: function(name, definition) {
 var cfg = this.cfg;
 if(name in this.steps) {
 throw new Error('the step "' + name + '" already exists for sequence "' + cfg.name + '"');
 }
 if(cfg.isFreezed) {
 throw new Error('cannot add "' + name + '" to the sequence "' + cfg.name + '" as it is freezed');
 }
 this.steps[name] = step.create(name, definition).on('proceed', this, '_handleProceed', this).on('relay', this, '_handleRelay', this);
 this.stepsCount++;
 if(this.stepsCount === 1) {
 this.stepsStartWith = name;
 }
 return this;
 },

 freeze: function() {
 var cfg = this.cfg;
 if(this.stepsCount === 0) {
 throw new Error('cannot freeze the sequence "' + cfg.name + '" as no steps are yet registered');
 }
 cfg.isFreezed = true;
 return this;
 },

 play: function(data) {
 var cfg = this.cfg;
 if(!cfg.isFreezed) {
 throw new Error('cannot play sequence "' + cfg.name + '" as it is not freezed');
 }
 if(!cfg.isPlaying) {
 cfg.isPlaying = true;
 this.data = data;
 this.dispatch('play');
 this.steps[this.stepsStartWith].play(data);
 }
 },

 _handleProceed: function(evt) {
 var target = evt.details.target;
 if(target === 'exit') {
 this.cfg.isPlaying = false;
 this.dispatch('end');
 } else {
 if(!(target in this.steps)) {
 throw new Error('cannot play "' + target + '" step in sequence "' + this.cfg.name + '"');
 }
 this.steps[target].play(this.data);
 }
 },

 _handleRelay: function(evt) {
 this.data.lastEvent = evt;
 }
 });
 });

 box.define('service/xhr', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 ajax: require('jquery').ajax,
 method: 'GET',
 timeout: 15000
 },

 send: function(settings) {
 if(this.transport) {
 throw new Error('xhr cannot send a request while another one is still pending');
 }
 var cfg = this.cfg;
 if(!settings) {
 settings = {};
 }
 if(!settings.url && !cfg.url) {
 throw new Error('missing url for xhr');
 }
 this.dispatch('beforeSend', settings);
 this.transport = cfg.ajax(this._transformSettings(settings));
 },

 abort: function() {
 if(this.transport) {
 this.transport.abort();
 }
 },

 _transformSettings: function(settings) {
 var defaults = require('object/defaults');
 var bind = require('function/bind');
 var cfg = this.cfg;
 var headers = settings.headers;
 var transformed = {
 dataType: settings.type,
 timeout: settings.timeout || cfg.timeout,
 type: settings.method || cfg.method,
 url: settings.url || cfg.url
 };
 if(headers) {
 transformed.contentType = headers['Content-Type'];
 headers['Content-Type'] = this.undef;
 }
 transformed.error = bind(this._errorCallback, this);
 transformed.success = bind(this._successCallback, this);
 return defaults(transformed, settings);
 },

 _errorCallback: function(transport, status, response) {
 this.transport = null;
 this.handleResponse(status, response, transport);
 },

 _successCallback: function(response, status, transport) {
 this.transport = null;
 this.handleResponse(status, response, transport);
 },

 handleResponse: function(status, response, transport) {
 var cfg = this.cfg;
 var details = {
 response: response,
 httpCode: transport.status,
 status: status,
 transport: transport
 };
 this.dispatch('complete', typeof cfg.resolver === 'function' ? cfg.resolver(details) : details);
 }
 });
 });

 box.define('service/iframeRegistry', function(require, module) {
 var count = 0;

 module.exports = {
 modules: {},

 add: function(iframe) {
 var key = module.id + '/' + (++count);
 this.modules[key] = iframe;
 return key;
 },

 remove: function(key) {
 this.modules[key] = this.undef;
 },

 get: function(key) {
 return this.modules[key] || null;
 }
 };
 });

 box.define('service/iframe', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 iframeClass: 'service-iframe',
 method: 'POST'
 },

 setup: function(cfg) {
 cfg.registry = require('service/iframeRegistry').add(this);
 this.callModule('events/target@setup', cfg);
 },

 send: function(settings) {
 if(this.transport) {
 throw new Error('iframe cannot send a request while another one is still pending');
 }
 var cfg = this.cfg;
 if(!settings) {
 settings = {};
 }
 if(!settings.url && !cfg.url) {
 throw new Error('missing url for iframe');
 }
 this.dispatch('beforeSend', settings);
 this._injectIframe(this._transformSettings(settings));
 },

 _transformSettings: function(settings) {
 var cfg = this.cfg;
 var transformed = {};
 transformed.url = settings.url || cfg.url;
 transformed.method = settings.method || cfg.method;
 transformed.data = settings.data || {};
 return transformed;
 },

 _injectIframe: function(settings) {
 var iframeElm = this.transport = document.createElement('iframe');
 iframeElm.className = this.cfg.iframeClass;
 iframeElm.style.cssText = 'position:absolute;left:-10000px;border:none;width:1px;height:1px;';
 if(typeof iframeElm.srcdoc === 'string') {
 iframeElm.srcdoc = this._buildDocHtml(settings);
 } else {
 iframeElm.src = 'javascript:\'' + this._buildDocHtml(settings) + '\';';
 }
 document.body.appendChild(iframeElm);
 },

 _buildDocHtml: function(settings) {
 return [
 '<!DOCTYPE html>',
 '<html>',
 '<head>',
 '<meta charset="UTF-8">',
 '<title></title>',
 '</head>',
 '<body onload="document.forms[0].submit();">',
 this._buildFormHtml(settings),
 '</body>',
 '</html>'
 ].join('');
 },

 _buildFormHtml: function(settings) {
 var html = [ '<form action="' + settings.url + '" method="' + settings.method + '">' ];
 var data = settings.data;
 var name;
 for(name in data) {
 if(data.hasOwnProperty(name)) {
 html.push('<input type="hidden" name="' + name + '" value="' + data[name] + '">');
 }
 }
 html.push('<input type="hidden" name="boxjsIframeRegistry" value="', this.cfg.registry, '">', '</form>');
 return html.join('');
 },

 handleResponse: function(response) {
 this.transport.parentNode.removeChild(this.transport);
 this.transport = null;
 var cfg = this.cfg;
 var details = {
 status: 'success',
 response: response
 };
 this.dispatch('complete', typeof cfg.resolver === 'function' ? cfg.resolver(details) : details);
 }
 });
 });

 box.define('service/xhrOrIframe', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 reSameDomain: /^[\/.]/
 },

 setup: function(cfg) {
 var defaults = require('object/defaults');
 this.xhr = require('service/xhr').create(defaults({}, cfg));
 this.iframe = require('service/iframe').create(defaults({}, cfg));
 this.xhr.on('complete', this, 'handleResponse');
 this.iframe.on('complete', this, 'handleResponse');
 this.callModule('events/target@setup', cfg);
 },

 isSameDomain: function(url) {
 if(this.cfg.reSameDomain.test(url)) {
 return true;
 }
 var base = window.location.protocol + '//' + window.location.hostname;
 return url.indexOf(base) > -1;
 },

 send: function(settings) {
 if(this.xhr.transport || this.iframe.transport) {
 throw new Error('xhrOrIframe cannot send a request while another one is still pending');
 }
 var cfg = this.cfg;
 if(!settings) {
 settings = {};
 }
 if(!settings.url) {
 settings.url = cfg.url;
 }
 if(!settings.url) {
 throw new Error('missing url for xhrOrIframe');
 }
 if(this.isSameDomain(settings.url)) {
 this.xhr.send(settings);
 } else {
 this.iframe.send(settings);
 }
 },

 handleResponse: function(evt) {
 this.dispatch(evt.type, evt.details);
 }
 });
 });

 box.define('dom/elmData', function(require, module) {
 var isObject = require('lang/isObject');
 var $j = require('jquery');
 var elmData = {
 ATTRIBUTE_NAME: 'data-boxjs',
 RE_TRIM_LEFT: /^\s+/,
 RE_TRIM_RIGHT: /\s+$/,
 RE_INTEGER: /^[+-]?[0-9]+$/,
 RE_FLOAT: /^[+-]?[0-9]*\.[0-9]+$/,

 get: function(elm, key) {
 var dataString = elm.getAttribute(elmData.ATTRIBUTE_NAME);
 var dataObject, parts, tokens, i, property, value;
 if(!dataString) {
 return null;
 }
 dataObject = {};
 parts = dataString.split(';');
 i = parts.length;
 while(i--) {
 if(parts[i]) {
 tokens = parts[i].split(':');
 if(tokens.length === 2) {
 property = decodeURIComponent(tokens[0].replace(elmData.RE_TRIM_LEFT, '').replace(elmData.RE_TRIM_RIGHT, ''));
 value = decodeURIComponent(tokens[1].replace(elmData.RE_TRIM_LEFT, '').replace(elmData.RE_TRIM_RIGHT, ''));
 dataObject[property] = value;
 }
 }
 }
 return key ? dataObject[key] || null : dataObject;
 },

 getTyped: function(elm, key) {
 var data = elmData.get(elm, key);
 if(typeof data === 'string') {
 return elmData.getTypedValue(data);
 }
 return elmData.convertDataToTyped(data);
 },

 getTypedValue: function(value) {
 if(value === 'true') {
 return true;
 }
 if(value === 'false') {
 return false;
 }
 if(elmData.RE_INTEGER.test(value)) {
 return parseInt(value, 10);
 }
 if(elmData.RE_FLOAT.test(value)) {
 return parseFloat(value);
 }
 return value;
 },

 convertDataToTyped: function(data) {
 for(var property in data) {
 if(data.hasOwnProperty(property)) {
 data[property] = elmData.getTypedValue(data[property]);
 }
 }
 return data;
 },

 convertDataToString: function(dataObject) {
 var dataArray = [];
 var property;
 for(property in dataObject) {
 if(dataObject.hasOwnProperty(property)) {
 dataArray.push(encodeURIComponent(property) + ':' + encodeURIComponent(dataObject[property]));
 }
 }
 return dataArray.length === 0 ? '' : dataArray.join(';') + ';';
 },

 set: function(elm) {
 var dataObject = elmData.get(elm) || {};
 var data, property;
 if(typeof arguments[1] === 'string') {
 property = arguments[1];
 dataObject[property] = arguments[2];
 } else if(isObject(arguments[1])) {
 data = arguments[1];
 for(property in data) {
 if(data.hasOwnProperty(property)) {
 dataObject[property] = data[property];
 }
 }
 }
 elm.setAttribute(elmData.ATTRIBUTE_NAME, elmData.convertDataToString(dataObject));
 },

 remove: function(elm) {
 var dataObject = elmData.get(elm);
 var i = arguments.length;
 if(dataObject) {
 while(--i > 0) {
 delete dataObject[arguments[i]];
 }
 elm.setAttribute(elmData.ATTRIBUTE_NAME, elmData.convertDataToString(dataObject));
 }
 },

 clear: function(elm) {
 if(elm.getAttribute(elmData.ATTRIBUTE_NAME)) {
 elm.setAttribute(elmData.ATTRIBUTE_NAME, '');
 }
 }
 };

 $j.fn.getBoxData = function(key) {
 return elmData.get(this[0], key);
 };
 $j.fn.getBoxTypedData = function(key) {
 return elmData.getTyped(this[0], key);
 };
 $j.fn.setBoxData = function(keyOrData, valueOrUndef) {
 return elmData.set(this[0], keyOrData, valueOrUndef);
 };
 $j.fn.removeBoxData = function() {
 var args = [ this[0] ];
 return elmData.remove.apply(elmData, args.concat(Array.prototype.slice.call(arguments)));
 };
 $j.fn.clearBoxData = function() {
 return elmData.clear(this[0]);
 };

 module.exports = elmData;
 });

 box.define('dom/elmComponents', function(require, module) {
 var elmComponents = {
 SELECTOR: '.boxjs',

 setup: function($root) {
 var elmData = require('dom/elmData');
 var defaults = require('object/defaults');

 $root.find(elmComponents.SELECTOR).each(function(index, elm) {
 var data = elmData.getTyped(elm);
 var target, component, parts, method;
 if(data && data.module) {
 parts = data.module.split('@');
 target = require(parts[0]);
 method = parts[1] || 'setup';
 if(!target) {
 component = data.component ? require(data.component) : null;
 if(component) {
 data.setupImmediatly = false;
 target = component.create(data);
 if(target) {
 box.define(data.module, target);
 }
 }
 if(!target) {
 throw new Error('cannot find "' + data.module + '" module');
 }
 }
 if(typeof target[method] !== 'function') {
 throw new Error('no "' + method + '" method on module "' + parts[0] + '"');
 }
 data.$core = $j(elm);
 if(target.cfg) {
 target.cfg = defaults(data, target.cfg);
 }
 target[method](data);
 }
 });
 },

 cleanup: function($root) {
 var elmData = require('dom/elmData');

 $root.find(elmComponents.SELECTOR).each(function(index, elm) {
 var id = elmData.get(elm, 'module');
 var target;
 if(id) {
 target = require(id);
 if(!target) {
 throw new Error('cannot cleanup the undefined "' + id + '" module');
 }
 if(typeof target.cleanup === 'function') {
 target.cleanup();
 }
 }
 });
 }
 };

 module.exports = elmComponents;
 });

 box.define('dom/elmOuterHtml', function(require, module) {
 module.exports = (function() {
 if(typeof document.documentElement.outerHTML === 'string') {
 return function(elm) {
 return elm.outerHTML;
 };
 } else {
 return function(elm) {
 var dummyElm = document.createElement('div');
 dummyElm.appendChild(elm.cloneNode(true));
 return dummyElm.innerHTML;
 };
 }
 }());
 });

 box.define('dom/region', function(require, module) {
 var region = {
 getTargetCoords: function(anchor, region) {
 var coords;
 switch(anchor) {
 case 'tl':
 coords = { top: 0, left: 0 };
 break;
 case 'tc':
 coords = { top: 0, left: -region.width / 2 };
 break;
 case 'tr':
 coords = { top: 0, left: -region.width };
 break;
 case 'cl':
 coords = { top: -region.height / 2, left: 0 };
 break;
 case 'cc':
 coords = { top: -region.height / 2, left: -region.width / 2 };
 break;
 case 'cr':
 coords = { top: -region.height / 2, left: -region.width };
 break;
 case 'bl':
 coords = { top: -region.height, left: 0 };
 break;
 case 'bc':
 coords = { top: -region.height, left: -region.width / 2 };
 break;
 case 'br':
 coords = { top: -region.height, left: -region.width };
 break;
 }
 return coords;
 },

 getRefCoords: function(anchor, region) {
 var coords;
 switch(anchor) {
 case 'tl':
 coords = { top: region.top, left: region.left };
 break;
 case 'tc':
 coords = { top: region.top, left: region.left + region.width / 2 };
 break;
 case 'tr':
 coords = { top: region.top, left: region.left + region.width };
 break;
 case 'cl':
 coords = { top: region.top + region.height / 2, left: region.left };
 break;
 case 'cc':
 coords = { top: region.top + region.height / 2, left: region.left + region.width / 2 };
 break;
 case 'cr':
 coords = { top: region.top + region.height / 2, left: region.left + region.width };
 break;
 case 'bl':
 coords = { top: region.top + region.height, left: region.left };
 break;
 case 'bc':
 coords = { top: region.top + region.height, left: region.left + region.width / 2 };
 break;
 case 'br':
 coords = { top: region.top + region.height, left: region.left + region.width };
 break;
 }
 return coords;
 },

 getAnchoredRegion: function(anchors, targetRegion, refRegion, options) {
 if(!options) {
 options = {};
 }
 options.allowTargetBeforeRef = options.allowTargetBeforeRef !== false;
 if(options.isRefContext) {
 targetRegion.top -= refRegion.top;
 targetRegion.left -= refRegion.left;
 refRegion.top = 0;
 refRegion.left = 0;
 }
 var isCoveringRef = options.coverRef === true;
 var parts = anchors.split('-');
 var targetCoords = region.getTargetCoords(parts[0], targetRegion);
 var refCoords = region.getRefCoords(parts[1], refRegion);
 var top = targetCoords.top + refCoords.top + (options.offsetTop || 0);
 var left = targetCoords.left + refCoords.left + (options.offsetLeft || 0);
 return {
 top: !options.allowTargetBeforeRef && top < refRegion.top ? refRegion.top : top,
 left: !options.allowTargetBeforeRef && left < refRegion.left ? refRegion.left : left,
 width: isCoveringRef ? refRegion.width : targetRegion.width,
 height: isCoveringRef ? refRegion.height : targetRegion.height
 };
 }
 };

 module.exports = region;
 });

 box.define('dom/elmRegion', function(require, module) {
 var region = require('dom/region');

 var elmRegion = {
 region: function($elm) {
 var region = $elm.offset();
 region.width = $elm.outerWidth();
 region.height = $elm.outerHeight();
 return region;
 },

 RE_POSITIONED: /^absolute|fixed|relative$/,

 computePosition: function($target, $ref, anchors, options) {
 if(!options) {
 options = {};
 }
 if($j.contains($ref[0], $target[0]) && elmRegion.RE_POSITIONED.test($ref.css('position'))) {
 options.isRefContext = true;
 }
 var targetRegion = elmRegion.region($target);
 var refRegion = elmRegion.region($ref);
 return region.getAnchoredRegion(anchors, targetRegion, refRegion, options);
 },

 position: function($target, $ref, anchors, options) {
 var region = elmRegion.computePosition($target, $ref, anchors, options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 center: function($target, $ref, options) {
 var region = elmRegion.computePosition($target, $ref, 'cc-cc', options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 cover: function($target, $ref) {
 var region = elmRegion.computePosition($target, $ref, 'tl-tl', { coverRef: true });
 $target.css(region);
 return region;
 }
 };

 module.exports = elmRegion;
 });

 box.define('dom/viewRegion', function(require, module) {
 var region = require('dom/region');
 var elmRegion = require('dom/elmRegion');

 var viewRegion = {
 region: function() {
 var $win = $j(window);
 var nRegionLeft = ('onorientationchange' in window)? 0 : $win.scrollLeft();
 return {
 top: $win.scrollTop(),
 left: nRegionLeft,
 width: $win.width(),
 height: $win.height()
 };
 },

 computePosition: function($target, anchors, options) {
 var targetRegion = elmRegion.region($target);
 var refRegion = viewRegion.region();
 return region.getAnchoredRegion(anchors, targetRegion, refRegion, options);
 },

 position: function($target, anchors, options) {
 var region = viewRegion.computePosition($target, anchors, options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 center: function($target, options) {
 var region = viewRegion.computePosition($target, 'cc-cc', options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 cover: function($target) {
 var region = viewRegion.computePosition($target, 'tl-tl', { coverRef: true });
 $target.css(region);
 return region;
 }
 };

 module.exports = viewRegion;
 });

 box.define('dom/docRegion', function(require, module) {
 var region = require('dom/region');
 var elmRegion = require('dom/elmRegion');

 var docRegion = {
 region: function() {
 var $doc = $j(document);
 return {
 top: 0,
 left: 0,
 width: $doc.width(),
 height: $doc.height()
 };
 },

 computePosition: function($target, anchors, options) {
 var targetRegion = elmRegion.region($target);
 var refRegion = docRegion.region();
 return region.getAnchoredRegion(anchors, targetRegion, refRegion, options);
 },

 position: function($target, anchors, options) {
 var region = docRegion.computePosition($target, anchors, options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 center: function($target, options) {
 var region = docRegion.computePosition($target, 'cc-cc', options);
 $target.css({ top: region.top, left: region.left });
 return region;
 },

 cover: function($target) {
 var region = docRegion.computePosition($target, 'tl-tl', { coverRef: true });
 $target.css(region);
 return region;
 }
 };

 module.exports = docRegion;
 });

 box.define('dom/evtDelegateManager', function(require, module) {
 var elmData = require('dom/elmData');
 var count = 0;

 var evtDelegateManager = {
 modules: {},

 registerModule: function(m) {
 var id = module.id + '/modules/' + (++count);
 evtDelegateManager.modules[id] = m;
 return id;
 },

 unregisterModule: function(id) {
 evtDelegateManager.modules[id] = evtDelegateManager.undef;
 },

 handleEvent: function(evt, reTagNames, maxIter) {
 var elm = evt.target;
 while(elm && maxIter > 0) {
 if(reTagNames.test(elm.tagName)) {
 evtDelegateManager.callComponent(elm, evt);
 break;
 }
 elm = elm.parentNode;
 maxIter--;
 }
 },

 callComponent: function(elm, evt) {
 var data = elmData.get(elm);
 var onevt = 'on' + evt.type;
 var path = data && data[onevt];
 var parts, target, method;
 if(path) {
 parts = path.split('@');
 target = require(parts[0]) || evtDelegateManager.modules[parts[0]];
 method = parts[1];
 if(!target) {
 throw new Error('cannot call ' + onevt + ' an undefined module ("' + path + '")');
 }
 if(typeof target[method] !== 'function') {
 throw new Error('cannot call onclick the undefined method "' + method + '" from module "' + parts[0] + '"');
 }
 data = elmData.convertDataToTyped(data);
 data.element = elm;
 data.event = evt;
 target[method](data);
 }
 }
 };

 module.exports = evtDelegateManager;
 });

 box.define('dom/evtDelegateClick', function(require, module) {
 var $j = require('jquery');
 var delegateHandler = require('dom/evtDelegateManager');

 var delegateClick = {
 RE_SUPPORTED_ELEMENTS: /^a|button$/i,
 isStopped: true,
 maxIter: 10,

 start: function(maxIter) {
 if(!isNaN(maxIter) && typeof maxIter !== 'boolean') {
 delegateClick.maxIter = maxIter;
 }
 if(delegateClick.isStopped) {
 $j(document).ready(function() {
 $j(document.body).bind('click.boxjsEvtDelegateClick', delegateClick.handleEvent);
 delegateClick.isStopped = false;
 });
 }
 },

 stop: function() {
 if(!delegateClick.isStopped) {
 $j(document.body).unbind('click.boxjsEvtDelegateClick');
 delegateClick.isStopped = true;
 }
 },

 handleEvent: function(evt) {
 delegateHandler.handleEvent(evt, delegateClick.RE_SUPPORTED_ELEMENTS, delegateClick.maxIter);
 }
 };

 module.exports = delegateClick;
 });

 box.define('dom/viewResizeManager', function(require, module) {
 var indexOf = require('array/indexOf');
 var remove = require('array/remove');
 var $j = require('jquery');

 module.exports = {
 callbacks: [],

 isListening: false,

 add: function(callback) {
 if(indexOf(this.callbacks, callback) === -1) {
 this.callbacks.push(callback);
 }
 if(!this.isListening) {
 var bind = require('function/bind');
 $j(window).bind('resize.boxjsViewResizeManager', bind(this.execCallbacks, this))
 .bind('orientationchange.boxjsViewResizeManager', bind(this.execCallbacks, this));
 this.isListening = true;
 }
 },

 remove: function(callback) {
 remove(this.callbacks, callback);
 if(this.callbacks.length === 0) {
 $j(window).unbind('.boxjsViewResizeManager');
 this.isListening = false;
 }
 },

 execCallbacks: function() {
 var callbacks = this.callbacks;
 var i = -1;
 var l = callbacks.length;
 while(++i < l) {
 callbacks[i]();
 }
 }
 };
 });

 box.define('dom/extractListModel', function(require, module) {
 module.exports = require('component/factory').create({
 setup: function(cfg) {
 if(!cfg.$core || !cfg.$core.jquery || !cfg.$core.length) {
 throw new Error('missing or invalid core element for extractListModel');
 }
 if(!cfg.items || typeof cfg.items !== 'string') {
 throw new Error('missing or invalid items selector for extractListModel');
 }

 this._extract(cfg);
 },

 _extract: function(cfg) {
 var elmData = require('dom/elmData');
 var elmOuterHtml = require('dom/elmOuterHtml');
 var data = this.data = [];
 cfg.$core.find(cfg.items).each(function(i, elm) {
 var localData = elmData.getTyped(elm) || {};
 localData.__index = i;
 localData.html = elmOuterHtml(elm);
 data[i] = localData;
 });
 this.reset();
 },

 getDataset: function(from, to) {
 if(isNaN(from)) {
 from = 0;
 }
 if(isNaN(to) || to < from) {
 to = this.dataLive.length - 1;
 }
 return this.dataLive.slice(from, to + 1);
 },

 getHtml: function(from, to) {
 var data = this.getDataset(from, to);
 var html = '';
 var i = -1;
 var l = data.length;
 while(++i < l) {
 html += data[i].html;
 }
 return html;
 },

 reset: function() {
 this.dataLive = this.data.slice(0);
 this.isReversed = false;
 return this;
 },

 reverse: function() {
 this.dataLive.reverse();
 this.isReversed = !this.isReversed;
 return this;
 },

 filterBy: function(property, value) {
 if(property) {
 var filtered = [];
 var data = this.dataLive;
 var i = -1;
 var l = data.length;
 if(typeof value !== 'undefined') {
 while(++i < l) {
 if(data[i][property] === value) {
 filtered.push(data[i]);
 }
 }
 } else {
 while(++i < l) {
 if(property in data[i]) {
 filtered.push(data[i]);
 }
 }
 }
 this.dataLive = filtered;
 }
 return this;
 },

 sortBy: function(property) {
 function sort(a, b) {
 if(a[property] < b[property]) {
 return -1;
 }
 if(a[property] > b[property]) {
 return 1;
 }
 return 0;
 }
 this.dataLive.sort(sort);
 return this;
 }
 });
 });

 box.define('fx/timeline', function(require, module) {
 var requestAnimFrame, cancelAnimFrame;
 var timeline = module.exports = {
 registered: [],
 lastTime: 0,
 requestId: 0,
 RAF: 'requestAnimationFrame',
 CAF: 'cancelAnimationFrame',

 managerIndexOf: function(manager) {
 var registered = timeline.registered;
 var i = registered.length;
 while(i--) {
 if(registered[i].manager === manager) {
 return i;
 }
 }
 return -1;
 },

 getRequestAnimationFrame: function() {
 var RAF = timeline.RAF;
 var CAF = timeline.CAF;
 if(window[RAF] && window[CAF]) {
 return { request: RAF, cancel: CAF };
 }
 var pRAF = RAF.charAt(0).toUpperCase() + RAF.substring(1);
 var pCAF = CAF.charAt(0).toUpperCase() + CAF.substring(1);
 var prefixes = [ 'ms', 'o', 'moz', 'webkit' ];
 var i = prefixes.length;
 var rname, cname;
 while(i--) {
 rname = prefixes[i] + pRAF;
 cname = prefixes[i] + pCAF;
 if(window[rname] && window[cname]) {
 return { request: rname, cancel: cname };
 }
 }
 return null;
 },

 defineAnimFrame: function() {
 var names = timeline.getRequestAnimationFrame();
 if(names) {
 requestAnimFrame = window[names.request];
 cancelAnimFrame = window[names.cancel];
 } else {
 requestAnimFrame = function(callback) {
 var time = new Date().getTime();
 var nextTime = Math.max(0, 16 - (time - timeline.lastTime));
 var id = setTimeout(function() {
 callback(time + nextTime);
 }, nextTime);
 timeline.lastTime = time + nextTime;
 return id;
 };
 cancelAnimFrame = function(id) {
 clearTimeout(id);
 };
 }
 },

 animate: function(time) {
 var registered = timeline.registered;
 var i = registered.length;
 var manager;
 if(time < 1e12) {
 // @see http://updates.html5rocks.com/2012/05/requestAnimationFrame-API-now-with-sub-millisecond-precision
 time += performance.timing.navigationStart;
 }
 if(i > 0) {
 while(i--) {
 manager = registered[i];
 if(manager) {
 manager.manager[manager.method](time - manager.start);
 }
 }
 timeline.requestId = requestAnimFrame(timeline.animate);
 } else {
 cancelAnimFrame(timeline.requestId);
 timeline.requestId = 0;
 }
 },

 start: function(manager, method, elapsed) {
 if(timeline.managerIndexOf(manager) > -1) {
 return;
 }
 if(!requestAnimFrame) {
 timeline.defineAnimFrame();
 }
 timeline.registered.push({
 manager: manager,
 method: method,
 start: new Date().getTime() - (!isNaN(elapsed) ? elapsed : 0)
 });
 if(timeline.requestId === 0) {
 timeline.lastTime = new Date().getTime();
 timeline.requestId = requestAnimFrame(timeline.animate);
 }
 },

 stop: function(manager) {
 var index = timeline.managerIndexOf(manager);
 if(index > -1) {
 timeline.registered.splice(index, 1);
 }
 },

 restart: function(manager) {
 var index = timeline.managerIndexOf(manager);
 if(index > -1) {
 timeline.registered[index].start = new Date().getTime();
 }
 }
 };
 });

 box.define('fx/easing', {
 DEFAULT_EASING: 'cssDefault',

 definitions: {
 // css easing
 cssDefault: [ 0.250, 0.100, 0.250, 1.000 ],
 cssIn:      [ 0.420, 0.000, 1.000, 1.000 ],
 cssOut:     [ 0.000, 0.000, 0.580, 1.000 ],
 cssInOut:   [ 0.420, 0.000, 0.580, 1.000 ],

 // Penner equation approximations from
 // Matthew Lein's Ceaser: http://matthewlein.com/ceaser/
 inQuad:     [ 0.550, 0.085, 0.680, 0.530 ],
 inCubic:    [ 0.550, 0.055, 0.675, 0.190 ],
 inQuart:    [ 0.895, 0.030, 0.685, 0.220 ],
 inQuint:    [ 0.755, 0.050, 0.855, 0.060 ],
 inSine:     [ 0.470, 0.000, 0.745, 0.715 ],
 inExpo:     [ 0.950, 0.050, 0.795, 0.035 ],
 inCirc:     [ 0.600, 0.040, 0.980, 0.335 ],
 outQuad:    [ 0.250, 0.460, 0.450, 0.940 ],
 outCubic:   [ 0.215, 0.610, 0.355, 1.000 ],
 outQuart:   [ 0.165, 0.840, 0.440, 1.000 ],
 outQuint:   [ 0.230, 1.000, 0.320, 1.000 ],
 outSine:    [ 0.390, 0.575, 0.565, 1.000 ],
 outExpo:    [ 0.190, 1.000, 0.220, 1.000 ],
 outCirc:    [ 0.075, 0.820, 0.165, 1.000 ],
 outBack:    [ 0.175, 0.885, 0.320, 1.275 ],
 inOutQuad:  [ 0.455, 0.030, 0.515, 0.955 ],
 inOutCubic: [ 0.645, 0.045, 0.355, 1.000 ],
 inOutQuart: [ 0.770, 0.000, 0.175, 1.000 ],
 inOutQuint: [ 0.860, 0.000, 0.070, 1.000 ],
 inOutSine:  [ 0.445, 0.050, 0.550, 0.950 ],
 inOutExpo:  [ 1.000, 0.000, 0.000, 1.000 ],
 inOutCirc:  [ 0.785, 0.135, 0.150, 0.860 ]
 },

 compute: (function() {
 // From Christian Effenberger:
 // http://www.netzgesta.de/dev/cubic-bezier-timing-function.html
 var cx, bx, ax, cy, by, ay, p1x, p1y, p2x, p2y;

 function sampleCurveX(t) {
 return ((ax * t + bx) * t + cx) * t;
 }

 function sampleCurveY(t) {
 return ((ay * t + by) * t + cy) * t;
 }

 function sampleCurveDerivativeX(t) {
 return (3 * ax * t + 2 * bx) * t + cx;
 }

 function solveEpsilon(duration) {
 return 1 / (200 * duration);
 }

 function solve(x, epsilon) {
 return sampleCurveY(solveCurveX(x, epsilon));
 }

 function fabs(n) {
 return n >= 0 ? n : 0 - n;
 }

 function solveCurveX(x, epsilon) {
 var t0, t1, t2, x2, d2, i;

 for(t2 = x, i = 0; i < 8; i++) {
 x2 = sampleCurveX(t2) - x;
 if(fabs(x2) < epsilon) {
 return t2;
 }
 d2 = sampleCurveDerivativeX(t2);
 if(fabs(d2) < 1e-6) {
 break;
 }
 t2 = t2 - x2 / d2;
 }

 t0 = 0;
 t1 = 1;
 t2 = x;

 if(t2 < t0) {
 return t0;
 }
 if(t2 > t1) {
 return t1;
 }

 while(t0 < t1) {
 x2 = sampleCurveX(t2);
 if(fabs(x2 - x) < epsilon) {
 return t2;
 }
 if(x > x2) {
 t0 = t2;
 } else {
 t1 = t2;
 }
 t2 = (t1 - t0) * 0.5 + t0;
 }

 return t2;
 }

 return function(time, duration, easingName) {
 if(duration < 0) {
 throw new Error('fx duration cannot be negative (' + duration + ')');
 }
 var easing = this.definitions[easingName];
 var percent;
 if(time === 0) {
 percent = 0;
 } else if(time > duration) {
 percent = 1;
 } else {
 percent = time / duration;
 }
 p1x = easing[0];
 p1y = easing[1];
 p2x = easing[2];
 p2y = easing[3];
 cx = 3 * p1x;
 bx = 3 * (p2x - p1x) - cx;
 ax = 1 - cx - bx;
 cy = 3 * p1y;
 by = 3 * (p2y - p1y) - cy;
 ay = 1 - cy - by;
 return solve(percent, solveEpsilon(duration / 1000));
 };
 }())
 });

 box.define('fx/cssSupport', function(require, module) {
 var css = {
 VENDOR_PREFIXES: [ 'o', 'ms', 'moz', 'webkit' ],
 RE_UPPERCASE: /[A-Z]/g,
 RE_DASHES: /-[a-z]/g,

 cache: {},

 get: function(property) {
 var cssName, jsName, htmlStyles, i, suffix, prefix, name;

 if(property.indexOf('-') > -1) {
 cssName = property;
 jsName = css.toJsString(property);
 } else {
 cssName = css.toCssString(property);
 jsName = property;
 }

 if(jsName in css.cache) {
 return css.cache[jsName];
 }

 htmlStyles = document.documentElement.style;

 if(typeof htmlStyles[jsName] === 'string') {
 css.cache[jsName] = { js: jsName, css: cssName, prefix: '' };
 return css.cache[jsName];
 }

 i = css.VENDOR_PREFIXES.length;
 suffix = jsName.charAt(0).toUpperCase() + jsName.substring(1);
 while(i--) {
 prefix = css.VENDOR_PREFIXES[i];
 name = prefix + suffix;
 if(typeof htmlStyles[name] === 'string') {
 css.cache[jsName] = { js: name, css: '-' + prefix + '-' + cssName, prefix: prefix };
 return css.cache[jsName];
 }
 }

 return null;
 },

 toJsString: function(property) {
 return property.replace(css.RE_DASHES, function(match, index) {
 return index > 0 ? match.substring(1).toUpperCase() : match.substring(1);
 });
 },

 toCssString: function(property) {
 return property.replace(css.RE_UPPERCASE, function(match, index) {
 return (index > 0 ? '-' : '') + match.toLowerCase();
 });
 }
 };

 module.exports = css;
 });

 box.define('fx/cssLength', function(require, module) {
 module.exports = require('component/factory').create({
 DEFAULT_FROM_VALUE: 0,
 RE_RELATIVE: /^(\+|-)((?:\d*\.)?\d+)$/,

 setup: function(cfg) {
 this.isPlaying = false;
 this.isEnded = false;
 this.isReversed = false;
 this.RE_PROPERTY = new RegExp(this.propertyCssText + '\\s*:[^;]+;', 'i');
 this.configure(cfg);
 },

 configure: function(cfg) {
 this.from = cfg.from;
 this.to = cfg.to;
 this.unit = cfg.unit || 'px';
 this.duration = this.initDuration = cfg.duration;
 this.delay = cfg.delay || 0;
 this.totalDuration = this.delay + this.duration;
 this.currentTime = 0;
 this.easingFactor = 0;
 this.easing = cfg.easing || require('fx/easing').DEFAULT_EASING;
 },

 computeFrom: function($elm) {
 if(!isNaN(this.from)) {
 this.initFrom = this.from;
 } else {
 this.initFrom = this.from = parseFloat($elm.css(this.property)) || this.DEFAULT_FROM_VALUE;
 }
 this.currentValue = this.initFrom;
 },

 computeTo: function() {
 if(typeof this.to === 'number') {
 this.initTo = this.to;
 } else if(typeof this.to === 'string') {
 var parts = this.to.match(this.RE_RELATIVE);
 var n;
 if(parts) {
 n = parseFloat(parts[2]);
 this.initTo = this.to = this.initFrom + (parts[1] === '-' ? - n : n);
 } else {
 throw new Error('invalid relative value for cssLength');
 }
 } else {
 throw new Error('unrecognized value for cssLength');
 }
 },

 computeCurrent: function(time) {
 if(time > this.delay) {
 if(time <= this.totalDuration) {
 var rtime = time > this.delay ? time - this.delay : 0;
 this.easingFactor = require('fx/easing').compute(rtime, this.duration, this.easing);
 this.currentTime = time;
 this.computeCurrentValue();
 } else {
 this.currentTime = 0;
 this.isEnded = true;
 }
 }
 },

 computeCurrentValue: function() {
 this.currentValue = Math.round(this.easingFactor * (this.to - this.from) + this.from);
 },

 getCurrentValue: function() {
 return this.propertyCssText + ':' + this.currentValue + this.unit + ';';
 },

 getAValue: function(value) {
 return this.propertyCssText + ':' + value + this.unit + ';';
 },

 play: function() {
 this.isPlaying = true;
 this.isEnded = false;
 this.duration = this.initDuration - (this.currentTime > this.delay ? this.currentTime - this.delay : 0);
 this.from = this.currentValue;
 },

 pause: function() {
 this.isPlaying = false;
 },

 reverse: function() {
 this.duration = this.currentTime ? this.currentTime - this.delay : this.initDuration;
 this.from = this.currentValue;
 if(this.from === this.initFrom) {
 if(this.currentTime && this.currentTime <= this.delay) {
 this.isReversed = true;
 this.to = this.from;
 } else {
 this.isReversed = false;
 this.to = this.initTo;
 }
 } else {
 this.isReversed = true;
 this.to = this.to === this.initTo ? this.initFrom : this.initTo;
 }
 if(this.isPlaying) {
 this.currentTime = 0;
 }
 },

 reset: function() {
 this.duration = this.initDuration;
 this.currentTime = 0;
 this.currentValue = this.from = this.initFrom;
 this.to = this.initTo;
 }
 });
 });

 box.define('fx/cssTop', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'fx/cssLength' ],

 property: 'top',
 propertyCssText: 'top'
 });
 });

 box.define('fx/cssLeft', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'fx/cssLength' ],

 property: 'left',
 propertyCssText: 'left'
 });
 });

 box.define('fx/cssWidth', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'fx/cssLength' ],

 property: 'width',
 propertyCssText: 'width'
 });
 });

 box.define('fx/cssHeight', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'fx/cssLength' ],

 property: 'height',
 propertyCssText: 'height'
 });
 });

 box.define('fx/cssOpacity', function(require, module) {
 var docElm = document.documentElement;
 var cssOpacity = {
 MIXINS: [ 'fx/cssLength' ],

 property: 'opacity',
 DEFAULT_FROM_VALUE: 1,
 IS_FILTER: typeof docElm.style.opacity !== 'string' && docElm.currentStyle && typeof docElm.style.filter === 'string',

 computeCurrentValue: function() {
 this.currentValue = Math.round((this.easingFactor * (this.to - this.from) + this.from) * 100) / 100;
 }
 };
 docElm = null;

 if(cssOpacity.IS_FILTER) {
 cssOpacity.propertyCssText = 'filter';
 cssOpacity.getCurrentValue = function() {
 return 'filter:alpha(opacity=' + (this.currentValue * 100) + ');';
 };
 cssOpacity.getAValue = function(value) {
 return 'filter:alpha(opacity=' + (value * 100) + ');';
 };
 } else {
 cssOpacity.propertyCssText = 'opacity';
 cssOpacity.getCurrentValue = function() {
 return this.propertyCssText + ':' + this.currentValue + ';';
 };
 cssOpacity.getAValue = function(value) {
 return this.propertyCssText + ':' + value + ';';
 };
 }

 module.exports = require('component/factory').create(cssOpacity);
 });

 box.define('fx/css', function(require, module) {
 var timeline = require('fx/timeline');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 mapCssTransitionEnd: {
 'moz': 'transitionend',
 'ms': 'msTransitionEnd',
 'o': 'otransitionend',
 'webkit': 'webkitTransitionEnd'
 },
 RE_TRANSITION: /(?:-[a-z]+-)?transition(?:-[a-z]+)*:[^;]+;/g,

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);
 this.isPlaying = false;
 this.isEnded = false;
 this.isReversed = false;
 this.currentTime = 0;
 this.configure(cfg);
 if(cfg.allowCssTransition !== false) {
 this.checkForCssTransition();
 }
 },

 checkForCssTransition: function() {
 var cssTransition = require('fx/cssSupport').get('transition');
 var defaults = require('object/defaults');
 if(cssTransition) {
 this.cssTransition = defaults({}, cssTransition);
 this.cssTransition.endEvent = this.mapCssTransitionEnd[this.cssTransition.prefix] || 'transitionend';
 }
 },

 configure: function(cfg) {
 if(!this.isPlaying) {
 if(this.fx) {
 this.fx = [];
 }
 this.$elm = cfg.$elm;
 this.currentTime = 0;
 this.totalDuration = 0;
 var fx = this.fx = [];
 var srcFx = cfg.fx;
 var i = -1;
 var l = srcFx.length;
 var item;
 while(++i < l) {
 item = require('fx/' + srcFx[i].type).create(srcFx[i]);
 item.computeFrom(this.$elm);
 item.computeTo(this.$elm);
 fx.push(item);
 if(item.totalDuration > this.totalDuration) {
 this.totalDuration = item.totalDuration + 16;
 // add 16ms to be sure to cover all frames (@fixme?)
 }
 }
 }
 },

 forEachFx: function(callback) {
 var fx = this.fx;
 var i = -1;
 var l = fx.length;
 while(++i < l) {
 callback(fx[i], i);
 }
 },

 handleFxEnd: function(evt) {
 if(evt && this.cssTransition) {
 if(this.cssTransition.properties.indexOf(evt.originalEvent.propertyName) === -1) {
 return;
 }
 var count = ++this.cssTransition.countEnd;
 if(count < this.fx.length) {
 return;
 }
 }
 this.isEnded = true;
 this.currentTime = 0;
 this.pause();
 if(this.cfg.reverseAtEnd !== false) {
 this.reverse();
 }
 if(this.cssTransition) {
 this.$elm.unbind(this.cssTransition.endEvent);
 this.$elm[0].style.cssText = this.$elm[0].style.cssText.replace(this.RE_TRANSITION, '');
 }
 this.dispatch('end');
 },

 renderStep: function(time) {
 if(time <= this.totalDuration) {
 var cssText = this.beforePlayCssText;
 this.forEachFx(function(fx) {
 fx.computeCurrent(time);
 cssText += fx.getCurrentValue();
 });
 this.$elm[0].style.cssText = cssText;
 this.currentTime = time;
 } else {
 this.handleFxEnd();
 }
 },

 play: function() {
 var that = this;
 if(!that.isPlaying) {
 that.isPlaying = true;
 that.isEnded = false;
 var cssText = that.$elm[0].style.cssText + ';';
 var hasCssTransition = !!that.cssTransition;
 var cssProperties = [];
 that.forEachFx(function(fx) {
 fx.play();
 cssText = cssText.replace(fx.RE_PROPERTY, '');
 if(hasCssTransition) {
 cssText += fx.getAValue(fx.to);
 cssProperties.push(fx.propertyCssText + ' ' + fx.duration + 'ms ' + fx.delay + 'ms');
 }
 });
 that.beforePlayCssText = cssText;
 that.dispatch('play');
 if(that.cssTransition) {
 cssText = cssText.replace(that.RE_TRANSITION, '');
 that.cssTransition.countEnd = 0;
 that.cssTransition.properties = cssProperties.join(', ');
 that.$elm.bind(that.cssTransition.endEvent, function(evt) {
 that.handleFxEnd(evt);
 });
 that.$elm[0].style.cssText = that.cssTransition.css + ':' + that.cssTransition.properties + ';' + cssText;
 } else {
 timeline.start(that, 'renderStep', that.currentTime);
 }
 }
 return this;
 },

 pause: function() {
 if(this.isPlaying) {
 timeline.stop(this);
 this.isPlaying = false;
 this.forEachFx(function(fx) {
 fx.pause();
 });
 if(this.cssTransition) {
 this.$elm.unbind(this.cssTransition.endEvent);
 }
 this.dispatch('pause');
 }
 return this;
 },

 reverse: function() {
 this.forEachFx(function(fx) {
 fx.reverse();
 });
 this.isReversed = !this.isReversed;
 timeline.restart(this);
 return this;
 },

 reset: function() {
 if(!this.isPlaying) {
 this.forEachFx(function(fx) {
 fx.reset();
 });
 this.currentTime = 0;
 }
 return this;
 }
 });
 });

 box.define('ui/bootstrap', function(require, module) {
 var $j = require('jquery');

 module.exports = require('component/factory').create({
 DEFAULTS: {
 isSetupDone: false
 },

 setup: function() {
 if(!this.cfg.isSetupDone) {
 var bind = require('function/bind');
 $j(document).ready(bind(this._handleDomReady, this));
 this.cfg.isSetupDone = true;
 }
 },

 startDelegation: function() {
 var cfg = this.cfg;
 if('delegateClick' in cfg) {
 require('dom/evtDelegateClick').start(cfg.delegateClick);
 }
 },

 _handleDomReady: function() {
 this.startDelegation();
 require('dom/elmComponents').setup($j(document.documentElement));
 }
 });
 });

 box.define('ui/element', function(require, module) {
 var $j = require('jquery');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 insertTarget: 'body',
 insertMethod: 'appendTo',
 isInDom: false,

 updateOnResize: false,
 resizeDebounce: 200,
 allowTargetBeforeRef: true
 },

 setup: function(cfg) {
 var $core = cfg.$core;
 if($core) {
 cfg.isInDom = $j.contains(document.documentElement, $core[0]);
 }
 if(cfg.updateOnResize) {
 this.updateOnResize(cfg.updateOnResize);
 }
 this.callModule('events/target@setup', cfg);
 },

 insert: function() {
 var cfg = this.cfg;
 if(cfg.$core) {
 cfg.$core[cfg.insertMethod](cfg.insertTarget);
 cfg.isInDom = true;
 } else if(cfg.coreHtml) {
 cfg.$core = $j(cfg.coreHtml)[cfg.insertMethod](cfg.insertTarget);
 cfg.isInDom = true;
 }
 return this;
 },

 remove: function() {
 var cfg = this.cfg;
 if(cfg.$core) {
 cfg.$core.remove();
 }
 cfg.isInDom = false;
 return this;
 },

 prependTo: function(target) {
 this._insertTo('prependTo', target);
 return this;
 },

 appendTo: function(target) {
 this._insertTo('appendTo', target);
 return this;
 },

 insertBefore: function(target) {
 this._insertTo('insertBefore', target);
 return this;
 },

 insertAfter: function(target) {
 this._insertTo('insertAfter', target);
 return this;
 },

 _insertTo: function(where, target) {
 var cfg = this.cfg;
 if(cfg.$core) {
 cfg.$core[where](target);
 }
 },

 replace: function(mixed) {
 var cfg = this.cfg;
 var isInDom = cfg.isInDom;
 var $replacement;
 if(typeof mixed === 'string') {
 if(isInDom) {
 $replacement = $j(mixed);
 cfg.$core.replaceWith($replacement);
 cfg.$core = $replacement;
 } else {
 cfg.$core = null;
 }
 cfg.coreHtml = mixed;
 } else if(mixed && mixed.jquery) {
 if(isInDom) {
 cfg.$core.replaceWith(mixed);
 }
 cfg.$core = mixed;
 }
 return this;
 },

 position: function(ref, anchors, options) {
 var cfg = this.cfg;
 if(cfg.isInDom) {
 if(!ref) {
 ref = cfg.positionRef;
 }
 if(!anchors) {
 anchors = cfg.positionAnchors;
 }
 if(!options) {
 options = { allowTargetBeforeRef: cfg.allowTargetBeforeRef };
 }
 if(ref === 'viewport') {
 require('dom/viewRegion').position(this.cfg.$core, anchors, options);
 } else if(ref === 'document') {
 require('dom/docRegion').position(this.cfg.$core, anchors, options);
 } else {
 require('dom/elmRegion').position(this.cfg.$core, ref, anchors, options);
 }
 this._storeLastPosition('position', ref, anchors, options);
 }
 return this;
 },

 center: function(ref, options) {
 var cfg = this.cfg;
 if(cfg.isInDom) {
 if(!ref) {
 ref = cfg.positionRef;
 }
 if(!options) {
 options = { allowTargetBeforeRef: cfg.allowTargetBeforeRef };
 }
 if(ref === 'viewport') {
 require('dom/viewRegion').center(this.cfg.$core, options);
 } else if(ref === 'document') {
 require('dom/docRegion').center(this.cfg.$core, options);
 } else {
 require('dom/elmRegion').center(this.cfg.$core, ref, options);
 }
 this._storeLastPosition('center', ref, null, options);
 }
 return this;
 },

 cover: function(ref) {
 var cfg = this.cfg;
 if(cfg.isInDom) {
 if(!ref) {
 ref = cfg.positionRef;
 }
 if(ref === 'viewport') {
 require('dom/viewRegion').cover(this.cfg.$core);
 } else if(ref === 'document') {
 require('dom/docRegion').cover(this.cfg.$core);
 } else {
 require('dom/elmRegion').cover(this.cfg.$core, ref);
 }
 this._storeLastPosition('cover', ref, null, null);
 }
 return this;
 },

 _storeLastPosition: function(method, ref, anchors, options) {
 var cfg = this.cfg;
 cfg.positionMethod = method;
 cfg.positionRef = ref;
 cfg.positionAnchors = anchors;
 cfg.positionOptions = options;
 },

 updateOnResize: function(isUpdating) {
 var cfg = this.cfg;
 var manager = require('dom/viewResizeManager');
 var bind = require('function/bind');
 var debounce = require('function/debounce');
 cfg.updateOnResize = isUpdating;
 if(isUpdating) {
 if(!cfg.debounced) {
 cfg.debounced = debounce(bind(this._handleResize, this), cfg.resizeDebounce);
 manager.add(cfg.debounced);
 }
 } else {
 manager.remove(cfg.debounced);
 cfg.debounced = null;
 }
 },

 _handleResize: function() {
 var cfg = this.cfg;
 var method = cfg.positionMethod;
 if(cfg.isInDom) {
 if(method === 'position') {
 this.position(cfg.positionRef, cfg.positionAnchors, cfg.positionOptions);
 } else if(method === 'center') {
 this.center(cfg.positionRef, cfg.positionOptions);
 } else if(method === 'cover') {
 this.cover(cfg.positionRef);
 }
 this.dispatch('resize');
 }
 }
 });
 });

 box.define('ui/inputText', function(require, module) {
 var elmData = require('dom/elmData');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 rootClass: 'ctrl-text',
 disabledClass: 'ctrl-disabled',
 errorClass: 'ctrl-error',
 errorMessageClass: 'ctrl-error-message',
 fieldsetClass: 'ctrl-fieldset',
 legendClass: 'ctrl-legend',
 offscreenClass: 'offscreen',

 coreTagName: 'input',
 coreType: 'text',
 errorMessageTagName: 'em',

 component: 'inputText',
 evtNamespace: '.boxjsInputText',

 requiredRule: 'isValueMissing',
 patternRule: 'isPatternMismatch',
 tooLongRule: 'isTooLong',
 tooShortRule: 'isTooShort',
 confirmRule: 'isConfirmMismatch',
 validationList: 'required pattern tooLong tooShort confirm',

 reValidation: /(?:(?:,\s*)?([a-z]+)\(([^)]*)\))/gi,
 reCompound: /^([^,]+)(?:,\s*(.+))?$/,
 reEmpty: /^\s*$/
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);

 var $core = cfg.$core;
 this._cfgElements(cfg, $core);
 this._cfgIsRequired(cfg, $core);
 this._cfgSpecifics(cfg, $core);
 this._cfgValidation(cfg, $core);
 },

 cleanup: function() {
 var cfg = this.cfg;
 cfg.$core.unbind(cfg.evtNamespace);
 },

 _cfgElements: function(cfg, $core) {
 if(this._cfgIsInvalidCore(cfg, $core)) {
 throw new Error('missing or invalid coreElm for ' + cfg.component);
 }
 cfg.initData = elmData.get($core[0]) || {};
 if(cfg.initData.clearValue === 'true' && cfg.initData.labelInField === 'true') {
 throw new Error('inputText "#' + $core.attr('id') + '" cannot have both "clearValue" and "labelInField" params');
 }
 },

 _cfgIsInvalidCore: function(cfg, $core) {
 return !$core || !$core.jquery || !$core.length || $core[0].tagName.toLowerCase() !== cfg.coreTagName || ($core[0].type !== cfg.coreType && $core[0].getAttribute('data-type') !== cfg.coreType);
 },

 _cfgIsRequired: function(cfg, $core) {
 cfg.isRequired = $core.attr('required') === 'required';
 },

 _cfgSpecifics: function(cfg, $core) {
 this._cfgClearRestoreValue(cfg, $core);
 this._cfgLabelInField(cfg, $core);
 this._cfgPattern(cfg, $core);
 this._cfgMaxLength(cfg, $core);
 },

 _cfgClearRestoreValue: function(cfg, $core) {
 if(cfg.initData.clearValue === 'true') {
 var bind = require('function/bind');
 $core.bind('focus' + cfg.evtNamespace, bind(this._handleFocusClearValue, this))
 .bind('blur' + cfg.evtNamespace, bind(this._handleBlurRestoreValue, this));
 }
 },

 _cfgLabelInField: function(cfg, $core) {
 if(cfg.initData.labelInField === 'true') {
 var bind = require('function/bind');
 $core.bind('focus' + cfg.evtNamespace, bind(this._handleFocusHideLabel, this))
 .bind('blur' + cfg.evtNamespace, bind(this._handleBlurShowLabel, this));
 }
 },

 _cfgPattern: function(cfg, $core) {
 var pattern = $core.attr('pattern');
 if(pattern) {
 cfg.pattern = new RegExp('^(?:' + pattern + ')$');
 }
 },

 _cfgMaxLength: function(cfg, $core) {
 var maxLength = parseInt($core.attr('maxlength'), 10);
 if(maxLength) {
 cfg.maxLength = maxLength;
 }
 },

 _cfgValidation: function(cfg, $core) {
 if(cfg.validation !== false) {
 var dataRules = this._extractValidationRules(cfg, $core);
 var rules = [];

 this._cfgSpecialValidations(cfg, $core, dataRules);

 var list = cfg.validationList.split(' ');
 var i = -1;
 var l = list.length;
 var name;
 while(++i < l) {
 name = list[i];
 if(dataRules[name]) {
 rules.push(dataRules[name]);
 }
 }

 if(rules.length) {
 var bind = require('function/bind');
 cfg.rules = rules;
 $core.bind('change' + cfg.evtNamespace, bind(this._handleChange, this));
 }
 }
 cfg.errorType = cfg.errorMessage = null;
 },

 _cfgSpecialValidations: function(cfg, $core, dataRules) {
 this._cfgRequiredRule(cfg, $core, dataRules);
 this._cfgPatternRule(cfg, $core, dataRules);
 this._cfgTooLongRule(cfg, $core, dataRules);
 this._cfgConfirmRule(cfg, $core, dataRules);
 },

 _cfgRequiredRule: function(cfg, $core, dataRules) {
 var isDeclared = 'required' in dataRules;
 if(cfg.isRequired) {
 if(isDeclared) {
 dataRules.required.method = cfg.requiredRule;
 } else {
 dataRules.required = { method: cfg.requiredRule };
 }
 } else if(isDeclared) {
 throw new Error('missing "required" HTML attribute (#' + $core.attr('id') + ')');
 }
 },

 _cfgPatternRule: function(cfg, $core, dataRules) {
 var isDeclared = 'pattern' in dataRules;
 if(cfg.pattern || cfg.defaultPattern) {
 if(isDeclared) {
 dataRules.pattern.method = cfg.patternRule;
 } else {
 dataRules.pattern = { method: cfg.patternRule };
 }
 } else if(isDeclared) {
 throw new Error('missing "pattern" HTML attribute or "defaultPattern" config (#' + $core.attr('id') + ')');
 }
 },

 _cfgTooLongRule: function(cfg, $core, dataRules) {
 if(!isNaN(cfg.maxLength)) {
 if('tooLong' in dataRules) {
 dataRules.tooLong.method = cfg.tooLongRule;
 } else {
 dataRules.tooLong = { method: cfg.tooLongRule };
 }
 }
 },

 _cfgConfirmRule: function(cfg, $core, dataRules) {
 if('confirm' in dataRules) {
 if(!cfg.isRequired) {
 throw new Error('"confirm" validation needs a "required" HTML attribute (#' + $core.attr('id') + ')');
 }
 for(var name in dataRules) {
 if(dataRules.hasOwnProperty(name)) {
 if(name !== 'confirm' && name in dataRules) {
 dataRules[name] = null;
 }
 }
 }
 }
 },

 _extractValidationRules: function(cfg, $core) {
 var dataValidation = cfg.validation = cfg.validation || cfg.initData.validation;
 var dataRules = {};
 if(dataValidation !== null && dataValidation !== 'false') {
 var parts, name, rule, specific;
 cfg.reValidation.lastIndex = 0; // important!
 while(parts = cfg.reValidation.exec(dataValidation)) {
 name = parts[1];
 rule = name + 'Rule';
 specific = '_extract' + name.charAt(0).toUpperCase() + name.substring(1) + 'Rule';
 if(specific in this) {
 dataRules[name] = this[specific](cfg, rule, parts[2]);
 } else if(rule in cfg) {
 dataRules[name] = {
 method: cfg[rule],
 message: parts[2] || this.undef
 };
 } else {
 throw new Error('unknown validation rule "' + name + '" (#' + $core.attr('id') + ')');
 }
 }
 }
 return dataRules;
 },

 _extractTooShortRule: function(cfg, rule, value) {
 var parts = value.match(cfg.reCompound);
 if(parts) {
 cfg.minLength = parseInt(parts[1], 10);
 if(isNaN(cfg.minLength)) {
 throw new Error('invalid number "' + parts[1] + '" for tooShort rule (#' + cfg.$core.attr('id') + ')');
 }
 return {
 method: cfg[rule],
 message: parts[2] || this.undef
 };
 }
 },

 _extractConfirmRule: function(cfg, rule, value) {
 var parts = value.match(cfg.reCompound);
 if(parts) {
 cfg.confirmTarget = parts[1];
 return {
 method: cfg[rule],
 message: parts[2] || this.undef
 };
 }
 },

 getValue: function() {
 return this.cfg.$core[0].value;
 },

 setValue: function(value) {
 this.cfg.$core[0].value = value;
 return this;
 },

 getDefaultValue: function() {
 return this.cfg.$core[0].defaultValue;
 },

 isDefaultValue: function() {
 return this.getValue() === this.getDefaultValue();
 },

 isValueMissing: function() {
 return this.cfg.reEmpty.test(this.getValue());
 },

 isPatternMismatch: function() {
 var cfg = this.cfg;
 var value = this.getValue();
 var pattern = cfg.pattern || cfg.defaultPattern;
 return cfg.reEmpty.test(value) ? false : pattern ? !pattern.test(this.getValue()) : false;
 },

 isTooLong: function() {
 return !isNaN(this.cfg.maxLength) ? this.getValue().length > this.cfg.maxLength : false;
 },

 isTooShort: function() {
 return !isNaN(this.cfg.minLength) ? this.getValue().length < this.cfg.minLength : false;
 },

 isConfirmMismatch: function() {
 var cfg = this.cfg;
 var coreId = cfg.$core.attr('id');
 var confirmId = cfg.confirmTarget;
 var confirmElm = document.getElementById(confirmId);
 if(!confirmElm) {
 throw new Error('cannot confirm non-existent form control "' + confirmId + '" from "' + coreId + '"');
 }
 if(cfg.initData.confirmIfDefault) {
 return confirmElm.value !== confirmElm.defaultValue && confirmElm.value !== this.getValue();
 }
 return confirmElm.value !== this.getValue();
 },

 isValid: function() {
 var cfg = this.cfg;
 var rules = cfg.rules;
 var i, l, item;
 if(!cfg.$core[0].disabled && rules) {
 i = -1;
 l = rules.length;
 while(++i < l) {
 item = rules[i];
 if(this[item.method]()) {
 cfg.errorType = item.method;
 cfg.errorMessage = item.message;
 return false;
 }
 }
 cfg.errorType = cfg.errorMessage = null;
 }
 return true;
 },

 validate: function() {
 if(this.isValid()) {
 this.insertValidMessage();
 this.dispatch('valid');
 } else {
 this.insertErrorMessage();
 this.dispatch('invalid');
 }
 return this;
 },

 _handleChange: function() {
 this.validate();
 this.dispatch('DOMChange');
 },

 _handleFocusClearValue: function() {
 if(this.isDefaultValue()) {
 this.setValue('');
 }
 this.dispatch('DOMFocus');
 },

 _handleBlurRestoreValue: function() {
 if(this.isValueMissing()) {
 this.setValue(this.getDefaultValue());
 }
 this.dispatch('DOMBlur');
 },

 _handleFocusHideLabel: function() {
 this.$label().addClass(this.cfg.offscreenClass);
 this.dispatch('DOMFocus');
 },

 _handleBlurShowLabel: function() {
 if(this.isValueMissing()) {
 this.$label().removeClass(this.cfg.offscreenClass);
 }
 this.dispatch('DOMBlur');
 },

 $core: function() {
 return this.cfg.$core;
 },

 $root: function() {
 return this.cfg.$core.parents('.' + this.cfg.rootClass);
 },

 $label: function() {
 return this.$root().find('label');
 },

 $fieldset: function() {
 return this.cfg.$core.parents('.' + this.cfg.fieldsetClass);
 },

 $legend: function() {
 return this.$fieldset().find('.' + this.cfg.legendClass);
 },

 $form: function() {
 return $j(this.cfg.$core[0].form);
 },

 insertErrorMessage: function() {
 var cfg = this.cfg;
 if(cfg.errorMessage) {
 this.removeValidMessage().removeErrorMessage();
 var hasFieldset = elmData.get(cfg.$core[0], 'fieldset') === 'true';
 var tagName = cfg.errorMessageTagName;
 var cls = cfg.errorMessageClass;
 var html = [ '<', tagName, ' id="', cfg.$core.attr('id'), '-', cls, '" class="', cls, '">', cfg.errorMessage, '</', tagName, '>' ].join('');
 if(hasFieldset) {
 this.$legend().append(html);
 } else {
 this.$label().append(html);
 }
 this.$root().addClass(cfg.errorClass);
 }
 return this;
 },

 removeErrorMessage: function() {
 var cfg = this.cfg;
 $j('#' + cfg.$core.attr('id') + '-' + cfg.errorMessageClass).remove();
 this.$root().removeClass(cfg.errorClass);
 return this;
 },

 insertValidMessage: function() {
 this.removeErrorMessage().removeValidMessage();
 return this;
 },

 removeValidMessage: function() {
 return this;
 },

 disable: function() {
 var cfg = this.cfg;
 if(!cfg.$core[0].disabled) {
 cfg.$core.each(function(i, elm) {
 elm.disabled = true;
 });
 this.$root().addClass(cfg.disabledClass);
 this.dispatch('disabled');
 }
 return this;
 },

 enable: function() {
 var cfg = this.cfg;
 if(cfg.$core[0].disabled) {
 cfg.$core.each(function(i, elm) {
 elm.disabled = false;
 });
 this.$root().removeClass(cfg.disabledClass);
 this.dispatch('enabled');
 }
 return this;
 }
 });
 });

 box.define('ui/inputEmail', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 coreType: 'email',
 component: 'inputEmail',
 evtNamespace: '.boxjsInputEmail',
 defaultPattern: /^\s*[\w-]+(\.[\w-]+)*@([\w-]+\.)+[A-Za-z]{2,7}\s*$/
 }
 });
 });

 box.define('ui/inputNumber', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 coreType: 'number',
 component: 'inputNumber',
 evtNamespace: '.boxjsInputNumber',
 rangeOverflowRule: 'isRangeOverflow',
 rangeUnderflowRule: 'isRangeUnderflow',
 validationList: 'required pattern rangeOverflow rangeUnderflow confirm'
 },

 _cfgSpecifics: function(cfg, $core) {
 this._cfgMinMax(cfg, $core);
 this._cfgClearRestoreValue(cfg, $core);
 this._cfgLabelInField(cfg, $core);
 },

 _cfgMinMax: function(cfg, $core) {
 // can't use element properties min/max for unsupported browser
 var min = parseFloat($core.attr('min'));
 var max = parseFloat($core.attr('max'));
 if(!isNaN(min)) {
 cfg.min = min;
 }
 if(!isNaN(max)) {
 cfg.max = max;
 }
 },

 isRangeOverflow: function() {
 var value = parseFloat(this.getValue());
 return !isNaN(value) && value > this.cfg.max;
 },

 isRangeUnderflow: function() {
 var value = parseFloat(this.getValue());
 return !isNaN(value) && value < this.cfg.min;
 },

 isTooLong: function() {
 return false;
 },

 isTooShort: function() {
 return false;
 }
 });
 });

 box.define('ui/inputPassword', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 coreType: 'password',
 component: 'inputPassword',
 evtNamespace: '.boxjsInputPassword'
 },

 _handleBlurShowLabel: function() {
 if(this.getValue().length === 0) {
 this.$label().removeClass(this.cfg.offscreenClass);
 }
 this.dispatch('DOMBlur');
 }
 });
 });

 box.define('ui/inputHidden', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 coreType: 'hidden',
 component: 'inputHidden',
 evtNamespace: '.boxjsinputHidden'
 }
 });
 });

 box.define('ui/inputCheckbox', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 rootClass: 'ctrl-checkable',
 checkedClass: 'ctrl-checked',
 focusedClass: 'ctrl-focused',
 checkedFocusedClass: 'ctrl-checked-focused',
 fauxClass: 'ctrl-faux-checkbox',
 fauxTagName: 'span',
 requiredRule: 'isNotChecked',
 validationList: 'required',
 coreType: 'checkbox',
 component: 'inputCheckbox',
 evtNamespace: '.boxjsInputCheckbox'
 },

 _cfgSpecifics: function(cfg, $core) {
 this._cfgStates(cfg, $core);
 this._cfgFauxCtrl(cfg, $core);
 },

 _cfgStates: function(cfg) {
 cfg.isChecked = this.isNotChecked() === false;
 cfg.isFocused = false;
 if(cfg.isChecked) {
 this.$root().addClass(cfg.checkedClass);
 }
 },

 _cfgFauxCtrl: function(cfg, $core) {
 var bind = require('function/bind');
 this.$label().prepend([ '<', cfg.fauxTagName, ' class="', cfg.fauxClass, '"></', cfg.fauxTagName, '>' ].join(''));
 $core.bind('click' + cfg.evtNamespace, bind(this._handleClick, this))
 .bind('focus' + cfg.evtNamespace, bind(this._handleFocus, this))
 .bind('blur' + cfg.evtNamespace, bind(this._handleBlur, this));
 },

 _handleClick: function() {
 var cfg = this.cfg;
 if(cfg.isChecked) {
 cfg.isChecked = false;
 if(cfg.isFocused) {
 this.$root().addClass(cfg.focusedClass).removeClass(cfg.checkedFocusedClass);
 } else {
 this.$root().removeClass(cfg.checkedClass);
 }
 } else {
 cfg.isChecked = true;
 if(cfg.isFocused) {
 this.$root().addClass(cfg.checkedFocusedClass).removeClass(cfg.focusedClass);
 } else {
 this.$root().addClass(cfg.checkedClass);
 }
 }
 this.validate();
 this.dispatch('DOMClick');
 },

 _handleFocus: function() {
 var cfg = this.cfg;
 cfg.isFocused = true;
 if(cfg.isChecked) {
 this.$root().addClass(cfg.checkedFocusedClass).removeClass(cfg.checkedClass);
 } else {
 this.$root().addClass(cfg.focusedClass);
 }
 this.dispatch('DOMFocus');
 },

 _handleBlur: function() {
 var cfg = this.cfg;
 cfg.isFocused = false;
 if(cfg.isChecked) {
 this.$root().addClass(cfg.checkedClass).removeClass(cfg.checkedFocusedClass);
 } else {
 this.$root().removeClass(cfg.focusedClass);
 }
 this.dispatch('DOMBlur');
 },

 isNotChecked: function() {
 return this.cfg.$core[0].checked === false;
 },

 check: function() {
 var cfg = this.cfg;
 var $root = this.$root();
 cfg.isChecked = cfg.$core[0].checked = true;
 if(cfg.isFocused) {
 $root.addClass(cfg.checkedFocusedClass).removeClass(cfg.focusedClass);
 } else {
 $root.addClass(cfg.checkedClass);
 }
 this.validate();
 return this;
 },

 uncheck: function() {
 var cfg = this.cfg;
 var $root = this.$root();
 cfg.isChecked = cfg.$core[0].checked = false;
 if(cfg.isFocused) {
 $root.addClass(cfg.focusedClass).removeClass(cfg.checkedFocusedClass);
 } else {
 $root.removeClass(cfg.checkedClass);
 }
 this.validate();
 return this;
 },

 isValueMissing: function() {
 return false;
 },

 isPatternMismatch: function() {
 return false;
 },

 isConfirmMismatch: function() {
 return false;
 },

 isTooLong: function() {
 return false;
 },

 isTooShort: function() {
 return false;
 }
 });
 });

 box.define('ui/inputRadio', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputCheckbox' ],

 DEFAULTS: {
 fauxClass: 'ctrl-faux-radio',
 coreType: 'radio',
 component: 'inputRadio',
 evtNamespace: '.boxjsInputRadio'
 },

 $label: function() {
 return this.$root().find('label[for="' + this.cfg.$core.attr('id') + '"]');
 }
 });
 });

 box.define('ui/inputRadioGroup', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputRadio' ],

 DEFAULTS: {
 component: 'inputRadioGroup'
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);

 var $core = cfg.$core;
 this._cfgElements(cfg, $core);
 this._cfgMembers(cfg, $core);
 },

 _cfgIsInvalidCore: function(cfg, $core) {
 if(!$core || !$core.jquery || $core.length < 2) {
 return true;
 }
 var i = $core.length;
 var name = $core[0].name;
 var inputElm;
 while(i--) {
 inputElm = $core[i];
 if(inputElm.tagName.toLowerCase() !== cfg.coreTagName || inputElm.type !== cfg.coreType || inputElm.name !== name) {
 return true;
 }
 }
 return false;
 },

 _cfgMembers: function(cfg, $core) {
 var inputRadio = require('ui/inputRadio');
 var members = cfg.members = [];
 var i = -1;
 var l = $core.length;
 var current, rules;
 while(++i < l) {
 current = inputRadio.create({ $core: $core.eq(i) });
 current.on('DOMClick', this, '_handleClick');
 if(current.cfg.isRequired) {
 rules = current.cfg.rules;
 current.cfg.rules = this.undef;
 }
 members.push(current);
 }
 if(rules) {
 cfg.rules = rules;
 }
 },

 _handleClick: function() {
 this.validate();
 },

 getChecked: function() {
 var members = this.cfg.members;
 var i = members.length;
 while(i--) {
 if(members[i].isNotChecked() === false) {
 return members[i];
 }
 }
 return null;
 },

 isNotChecked: function() {
 return this.getChecked() === null;
 },

 check: function(id) {
 var type = typeof id;
 var members = this.cfg.members;
 var i;
 if(type === 'number' && members[id]) {
 members[id].check();
 } else if(type === 'string') {
 i = members.length;
 while(i--) {
 if(members[i].cfg.$core.attr('id') === id) {
 members[i].check();
 break;
 }
 }
 }
 this.validate();
 return this;
 },

 uncheck: function() {
 var checked = this.getChecked();
 if(checked) {
 checked.uncheck();
 }
 this.validate();
 return this;
 },

 getValue: function() {
 var checked = this.getChecked();
 return checked ? checked.getValue() : '';
 },

 setValue: function(value) {
 var checked = this.getChecked();
 if(checked) {
 checked.setValue(value);
 }
 return this;
 },

 $label: function() {
 var checked = this.getChecked();
 return checked ? checked.$label() : $j();
 }
 });
 });

 box.define('ui/textarea', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 coreTagName: 'textarea',
 component: 'textarea',
 evtNamespace: '.boxjsTextarea'
 },

 _cfgIsInvalidCore: function(cfg, $core) {
 return !$core || !$core.jquery || !$core.length || $core[0].tagName.toLowerCase() !== cfg.coreTagName;
 }
 });
 });

 box.define('ui/select', function(require, module) {
 var elmData = require('dom/elmData');
 var isArray = require('lang/isArray');

 module.exports = require('component/factory').create({
 MIXINS: [ 'ui/inputText' ],

 DEFAULTS: {
 rootClass: 'ctrl-select',
 requiredRule: 'isUselessSelection',
 validationList: 'required',
 coreTagName: 'select',
 coreType: 'select-one',
 component: 'select',
 evtNamespace: '.boxjsSelect'
 },

 _cfgSpecifics: function() {},

 getIndex: function() {
 var selectElm = this.cfg.$core[0];
 return selectElm.options.length ? selectElm.selectedIndex : -1;
 },

 setIndex: function(index) {
 var selectElm = this.cfg.$core[0];
 if(index >= 0 && index < selectElm.options.length) {
 selectElm.selectedIndex = index;
 }
 return this;
 },

 getValue: function() {
 var selectElm = this.cfg.$core[0];
 var optionElm = selectElm.options[selectElm.selectedIndex];
 return optionElm ? optionElm.value : '';
 },

 setValue: function(value) {
 if(typeof value !== 'string') {
 value += '';
 }
 var selectElm = this.cfg.$core[0];
 var options = selectElm.options;
 var i = options.length;
 while(i--) {
 if(options[i].value === value) {
 selectElm.selectedIndex = i;
 break;
 }
 }
 return this;
 },

 getText: function() {
 var index = this.getIndex();
 return index > -1 ? this.cfg.$core[0].options[index].text : '';
 },

 isDefaultSelected: function() {
 var index = this.getIndex();
 return index > -1 ? this.cfg.$core[0].options[index].defaultSelected : false;
 },

 isDefaultValue: null,

 isUselessSelection: function() {
 var index = this.getIndex();
 var optionElm = this.cfg.$core[0].options[index];
 return optionElm ? elmData.get(optionElm, 'useless') === 'true' : false;
 },

 clearOptions: function() {
 this.cfg.$core[0].options.length = 0;
 return this;
 },

 addOneOption: function(text, value, selected) {
 var selectElm = this.cfg.$core[0];
 var index = selectElm.options.length;
 var optionElm = document.createElement('option');
 optionElm.text = text;
 if(typeof value === 'string') {
 optionElm.value = value;
 }
 selectElm.options[index] = optionElm;
 if(selected === true) {
 selectElm.selectedIndex = index;
 }
 return this;
 },

 addOptions: function(options) {
 if(!isArray(options)) {
 throw new Error('addOptions requires an array of options (#' + this.cfg.$core.attr('id') + ')');
 }
 var i = -1;
 var l = options.length;
 var option;
 while(++i < l) {
 option = options[i];
 this.addOneOption(option.text, option.value, option.selected);
 }
 return this;
 },

 isValueMissing: function() {
 return false;
 },

 isPatternMismatch: function() {
 return false;
 },

 isTooLong: function() {
 return false;
 },

 isTooShort: function() {
 return false;
 }
 });
 });

 box.define('ui/form', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 submitClass: 'form-submit',
 loadingClass: 'form-loading',

 errorClass: 'form-error',
 errorTagName: 'div',
 errorInsertMethod: 'prependTo',
 errorInsertTarget: null,

 validation: true,
 errorOffsetTop: 0,

 'checkbox': 'ui/inputCheckbox',
 'email': 'ui/inputEmail',
 'number': 'ui/inputNumber',
 'password': 'ui/inputPassword',
 'radio': 'ui/inputRadioGroup',
 'select-one': 'ui/select',
 'text': 'ui/inputText',
 'textarea': 'ui/textarea',
 'hidden': 'ui/inputHidden',

 evtNamespace: '.boxjsForm'
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);
 this._cfgForm(cfg, cfg.$core);
 this._cfgService(cfg);

 cfg.error = false;
 cfg.isSubmitting = false;
 },

 cleanup: function() {
 var cfg = this.cfg;
 var fields = this.fields;
 var id;
 cfg.$core.unbind(cfg.evtNamespace);
 if(cfg.service) {
 cfg.service.off('complete', this);
 }
 for(id in fields) {
 if(fields.hasOwnProperty(id)) {
 fields[id].cleanup();
 }
 }
 },

 _cfgForm: function(cfg, $core) {
 if(!$core || !$core.jquery || !$core.length || $core[0].tagName !== 'FORM') {
 throw new Error('missing or invalid core element for form');
 }
 if(typeof $core.attr('novalidate') !== 'string') {
 throw new Error('missing attribute "novalidate" on form');
 }
 this._cfgFields(cfg, $core);
 var bind = require('function/bind');
 $core.bind('submit' + cfg.evtNamespace, bind(this._handleSubmit, this));
 },

 _cfgFields: function(cfg, $core) {
 var fields = this.fields = {};
 var elements = $core[0].elements;
 var i = -1;
 var l = elements.length;
 var elm, type, $elm, $radioGroup;
 while(++i < l) {
 elm = elements[i];
 type = elm.getAttribute('data-type') || elm.type;
 if(type in cfg) {
 $elm = $j(elm);
 if(type === 'radio') {
 if($radioGroup) {
 if(elm.name !== $radioGroup[0].name) {
 this._cfgRadioGroup(cfg, fields, $radioGroup);
 $radioGroup = $elm;
 } else {
 $radioGroup = $radioGroup.add(elm);
 }
 } else {
 $radioGroup = $elm;
 }
 } else {
 this._cfgRadioGroup(cfg, fields, $radioGroup);
 $radioGroup = null;
 fields[elm.id] = require(cfg[type]).create({ $core: $elm });
 }
 }
 }
 this._cfgRadioGroup(cfg, fields, $radioGroup);
 },

 _cfgRadioGroup: function(cfg, fields, $radioGroup) {
 if($radioGroup) {
 var id = $radioGroup[0].name;
 fields[id] = require(cfg.radio).create({ $core: $radioGroup });
 }
 },

 _cfgService: function(cfg) {
 if(cfg.service) {
 if(typeof cfg.service === 'string') {
 cfg.service = require(cfg.service);
 }
 cfg.service.on('complete', this, '_handleServiceComplete', this);
 }
 },

 isValid: function() {
 var fields = this.fields;
 var id;
 for(id in fields) {
 if(fields.hasOwnProperty(id) && !fields[id].isValid()) {
 return false;
 }
 }
 return true;
 },

 validate: function() {
 var fields = this.fields;
 var isValid = true;
 var id;
 for(id in fields) {
 if(fields.hasOwnProperty(id)) {
 fields[id].validate();
 if(fields[id].cfg.errorType) {
 isValid = false;
 }
 }
 }
 if(isValid) {
 this.cfg.error = false;
 this.removeErrorMessage();
 this.dispatch('valid');
 } else {
 this.cfg.error = true;
 this.insertErrorMessage();
 this.dispatch('invalid');
 }
 return this;
 },

 insertErrorMessage: function() {
 var cfg = this.cfg;
 if(cfg.errorMessage) {
 this.removeErrorMessage();
 var tagName = cfg.errorTagName;
 var cls = cfg.errorClass;
 var html = [ '<', tagName, ' class="', cls, '" tabindex="-1">', cfg.errorMessage, '</', tagName, '>' ].join('');
 var $target = cfg.errorInsertTarget ? cfg.$core.find(cfg.errorInsertTarget) : cfg.$core;
 var $error = $j(html)[cfg.errorInsertMethod]($target);
 if(cfg.errorScrollIntoView !== false) {
 var offsetTop = cfg.errorOffsetTopTarget ? - ($j(cfg.errorOffsetTopTarget).outerHeight() || 0) : cfg.errorOffsetTop;
 window.scrollTo(0, $error.offset().top + offsetTop);
 $error.focus();
 }
 }
 return this;
 },

 removeErrorMessage: function() {
 var cfg = this.cfg;
 cfg.$core.find('.' + cfg.errorClass).remove();
 return this;
 },

 displayLoadState: function() {
 var cfg = this.cfg;
 cfg.$core.addClass(cfg.loadingClass);
 return this;
 },

 displayReadyState: function() {
 var cfg = this.cfg;
 cfg.$core.removeClass(cfg.loadingClass);
 return this;
 },

 submit: function() {
 var cfg = this.cfg;
 if(!cfg.isSubmitting) {
 cfg.$core.submit();
 }
 },

 serialize: function() {
 return this.cfg.$core.serialize();
 },

 getValues: function() {
 var fields = this.fields;
 var values = {};
 var id, name, process;
 for(id in fields) {
 if(fields.hasOwnProperty(id) && !fields[id].cfg.$core[0].disabled) {
 process = true;
 if(typeof fields[id].isNotChecked === 'function') {
 if(fields[id].isNotChecked()) {
 process = false;
 }
 }
 if(process) {
 name = fields[id].cfg.$core.attr('name');
 values[name] = fields[id].getValue();
 }
 }
 }
 return values;
 },

 _handleSubmit: function(evt) {
 var cfg = this.cfg;
 if(cfg.validation) {
 this.validate();
 if(cfg.error) {
 evt.preventDefault();
 } else {
 this._handleSend(evt);
 }
 } else {
 this._handleSend(evt);
 }
 },

 _handleSend: function(evt) {
 var cfg = this.cfg;
 cfg.isSubmitting = true;
 this.displayLoadState();
 this.dispatch('send');
 if(cfg.service) {
 evt.preventDefault();
 cfg.service.send({
 url: cfg.$core.attr('action'),
 method: cfg.$core[0].method.toUpperCase(),
 data: this.getValues()
 });
 }
 },

 _handleServiceComplete: function(evt) {
 var cfg = this.cfg;
 cfg.$core.removeClass(cfg.loadingClass);
 this.dispatch('sendComplete', evt.details);
 }
 });
 });

 box.define('ui/pagination', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 perPage: 10,
 skipAbove: 100000,
 circular: false,
 insertMethod: 'appendTo',
 hasPreviousNext: false,
 hasFirstLast: false,
 rootHtml: '<div class="pagination"><ul>{$items}</ul></div>',
 itemHtml: '<li class="page-item"><a href="#" data-boxjs="onclick:{$module}@_handleClick; page:{$page};">{$label}</a></li>',
 itemSelectedHtml: '<li class="page-selected"><span>{$label}</span></li>',
 itemSkippedHtml: '<li class="page-skipped"><span>…</span></li>',
 itemPreviousHtml: '<li class="page-previous"><a href="#" data-boxjs="onclick:{$module}@_handleClick; page:previous;">{$label}</a></li>',
 itemNextHtml: '<li class="page-next"><a href="#" data-boxjs="onclick:{$module}@_handleClick; page:next;">{$label}</a></li>',
 itemFirstHtml: '<li class="page-first"><a href="#" data-boxjs="onclick:{$module}@_handleClick; page:first;">{$label}</a></li>',
 itemLastHtml: '<li class="page-last"><a href="#" data-boxjs="onclick:{$module}@_handleClick; page:last;">{$label}</a></li>',

 reItems: /\{\$items\}/,
 reModule: /\{\$module\}/,
 rePage: /\{\$page\}/g,
 reLabel: /\{\$label\}/,
 reSkipped: /(?:__SKIPPED__)+/g
 },

 setup: function(cfg) {
 if(!cfg.$target || !cfg.$target.jquery || !cfg.$target.length) {
 throw new Error('missing insertion target for pagination');
 }
 if(cfg.hasPreviousNext === true) {
 if(!cfg.l10n || !cfg.l10n.previous || !cfg.l10n.next) {
 throw new Error('missing labels for pagination previous and next buttons');
 }
 } else if(cfg.hasNumbers === false) {
 throw new Error('missing pagination previous/next buttons when numbers are not displayed');
 }

 cfg.delegateId = require('dom/evtDelegateManager').registerModule(this);
 this.callModule('events/target@setup', cfg);
 this.setTotal(cfg.total);
 },

 cleanup: function() {
 this.remove();
 },

 setTotal: function(total) {
 if(isNaN(total) || total < 0) {
 throw new Error('invalid total number of items (' + total + ') for pagination');
 }
 var cfg = this.cfg;
 cfg.total = total;
 cfg.pages = Math.ceil(cfg.total / cfg.perPage);
 cfg.current = -1; // force rendering
 this.render(1);
 },

 normalizePage: function(page) {
 var cfg = this.cfg;
 var modulo;
 if(page >= 1 && page <= cfg.pages) {
 return page;
 }
 if(cfg.circular) {
 modulo = page % cfg.pages;
 if(modulo === 0) {
 return cfg.pages;
 }
 return modulo < 1 ? modulo + cfg.pages : modulo;
 }
 return -1;
 },

 remove: function() {
 if(this.cfg.$core) {
 this.cfg.$core.remove();
 this.cfg.$core = null;
 }
 },

 render: function(page, action) {
 var cfg = this.cfg;
 page = this.normalizePage(page);
 if(page !== -1 && page !== cfg.current) {
 var evt = this.dispatch('beforeChange', { page: page, action: action });
 if(!evt || evt.isDefaultPrevented !== true) {
 this.remove();
 if(cfg.pages > 1) {
 var html = this._getHtml(page);
 cfg.$core = $j(html)[cfg.insertMethod](cfg.$target);
 }
 cfg.current = page;
 this.dispatch('change', { page: page, action: action });
 }
 }
 },

 _getHtml: function(page) {
 var cfg = this.cfg;
 var itemsFullHtml = '';
 var itemHtml = cfg.itemHtml;
 var itemLabel = cfg.l10n && cfg.l10n.item ? cfg.l10n.item : '{$page}';
 var reModule = cfg.reModule;
 var rePage = cfg.rePage;
 var reLabel = cfg.reLabel;
 var delegateId = cfg.delegateId;
 var min = page - cfg.skipAbove;
 var max = page + cfg.skipAbove;
 var skipped = '__SKIPPED__';
 var i = 0;
 var l = cfg.pages;
 if(cfg.hasFirstLast && page !== 1) {
 itemsFullHtml += cfg.itemFirstHtml.replace(reModule, delegateId).replace(reLabel, cfg.l10n.first);
 }
 if(cfg.hasPreviousNext && (page !== 1 || cfg.circular)) {
 itemsFullHtml += cfg.itemPreviousHtml.replace(reModule, delegateId).replace(reLabel, cfg.l10n.previous);
 }
 if(cfg.hasNumbers !== false) {
 while(++i <= l) {
 if(i === 1 || (i >= min && i <= max) || i === l) {
 if(i === page) {
 itemsFullHtml += cfg.itemSelectedHtml.replace(reLabel, itemLabel).replace(rePage, i);
 } else {
 itemsFullHtml += itemHtml.replace(reModule, delegateId).replace(reLabel, itemLabel).replace(rePage, i);
 }
 } else {
 itemsFullHtml += skipped;
 }
 }
 }
 if(cfg.hasPreviousNext && (page !== cfg.pages || cfg.circular)) {
 itemsFullHtml += cfg.itemNextHtml.replace(reModule, delegateId).replace(reLabel, cfg.l10n.next);
 }
 if(cfg.hasFirstLast && page !== cfg.pages) {
 itemsFullHtml += cfg.itemLastHtml.replace(reModule, delegateId).replace(reLabel, cfg.l10n.last);
 }
 return cfg.rootHtml.replace(cfg.reItems, itemsFullHtml.replace(cfg.reSkipped, cfg.itemSkippedHtml));
 },

 nth: function(page) {
 this.render(page, 'nth');
 },

 previous: function() {
 this.render(this.cfg.current - 1, 'previous');
 },

 next: function() {
 this.render(this.cfg.current + 1, 'next');
 },

 first: function() {
 this.render(1, 'first');
 },

 last: function() {
 this.render(this.cfg.pages, 'last');
 },

 getOffset: function() {
 var cfg = this.cfg;
 var perPage = cfg.perPage;
 var from = (cfg.current - 1) * perPage;
 return {
 from: from,
 to: Math.min(from + (perPage - 1), cfg.total - 1)
 };
 },

 _handleClick: function(data) {
 data.event.preventDefault();
 var page = data.page;
 if(typeof page === 'number') {
 this.nth(page);
 } else if(page in this) {
 this[page]();
 }
 }
 });
 });

 box.define('ui/listing', function(require, module) {
 var isObject = require('lang/isObject');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 reRiskyInsertMethod: /^(?:ap|pre)pendTo$/
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);
 this._cfgModel(cfg);
 this._cfgPagination(cfg);
 },

 _cfgModel: function(cfg) {
 if(!isObject(cfg.model)) {
 throw new Error('missing model information for listing');
 }
 if(!cfg.model.$core) {
 cfg.model.$core = cfg.$core;
 }
 this._createModel(cfg.model);
 },

 _createModel: function(cfg) {
 this.model = require('dom/extractListModel').create(cfg);
 },

 _cfgPagination: function(cfg) {
 if(isObject(cfg.pagination)) {
 var cfgPagination = cfg.pagination;
 var insertMethod = cfgPagination.insertMethod || 'appendTo';
 if(!cfgPagination.$target) {
 cfgPagination.$target = cfg.$core;
 }
 if(cfgPagination.$target[0] === cfg.model.$core[0] && cfg.reRiskyInsertMethod.test(insertMethod)) {
 throw new Error('the listing pagination will be erased with the current config (same insertion target)');
 }
 if(!cfgPagination.listeners) {
 cfgPagination.listeners = require('events/listeners').create();
 }
 cfgPagination.listeners.add('change', this, '_handlePageChange');
 cfgPagination.total = this.model.dataLive.length;
 this._createPagination(cfgPagination);
 }
 },

 _createPagination: function(cfg) {
 this.pagination = require('ui/pagination').create(cfg);
 },

 _handlePageChange: function(evt) {
 var offset = evt.source.getOffset();
 var html = this.model.getHtml(offset.from, offset.to);
 this.model.cfg.$core.html(html);
 this.dispatch('change');
 },

 nth: function(page) {
 if(this.pagination) {
 this.pagination.nth(page);
 }
 },

 previous: function() {
 if(this.pagination) {
 this.pagination.previous();
 }
 },

 next: function() {
 if(this.pagination) {
 this.pagination.next();
 }
 },

 first: function() {
 if(this.pagination) {
 this.pagination.first();
 }
 },

 last: function() {
 if(this.pagination) {
 this.pagination.last();
 }
 },

 reset: function() {
 this.model.reset();
 return this;
 },

 reverse: function() {
 this.model.reverse();
 return this;
 },

 filterBy: function(property, value) {
 this.model.filterBy(property, value);
 return this;
 },

 sortBy: function(property) {
 this.model.sortBy(property);
 return this;
 },

 render: function() {
 if(this.pagination) {
 this.pagination.setTotal(this.model.dataLive.length);
 } else {
 var html = this.model.getHtml();
 this.model.cfg.$core.html(html);
 this.dispatch('change');
 }
 return this;
 }
 });
 });

 box.define('ui/carouselCircular', function(require, module) {
 var isObject = require('lang/isObject');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 rootClass: 'carousel-circular',
 windowClass: 'carousel-window',
 movableClass: 'carousel-movable',
 itemClass: 'carousel-item',
 itemSelectedClass: 'carousel-item-selected',
 horizontalClass: 'carousel-hl',
 verticalClass: 'carousel-vl'
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);
 this._cfgElements(cfg);
 this._cfgCss(cfg);
 this._cfgSequence(cfg);
 this._cfgPagination(cfg);
 this._setSelectedItem(cfg);
 },

 cleanup: function() {
 this.pagination.cleanup();
 },

 _cfgElements: function(cfg) {
 var $core = cfg.$core;
 if(!$core || !$core.jquery || !$core.length || !$core.hasClass(cfg.rootClass)) {
 throw new Error('missing or invalid core element for carouselCircular');
 }
 cfg.$win = $core.find('.' + cfg.windowClass);
 if(cfg.$win.length === 0) {
 throw new Error('missing window element for carouselCircular');
 }
 cfg.$movable = cfg.$win.find('.' + cfg.movableClass);
 if(cfg.$movable.length === 0) {
 throw new Error('missing movable element for carouselCircular');
 }
 cfg.$items = cfg.$movable.find('.' + cfg.itemClass);
 },

 _cfgCss: function(cfg) {
 cfg.orientation = cfg.$core.hasClass(cfg.verticalClass) ? 'vertical' : 'horizontal';
 cfg.cssProperty = cfg.orientation === 'vertical' ? 'top' : 'left';
 if(cfg.orientation === 'vertical') {
 cfg.windowSize = cfg.windowSize || cfg.$win.height();
 cfg.itemSize = cfg.itemSize || cfg.$items.outerHeight(true);
 } else {
 cfg.windowSize = cfg.windowSize || cfg.$win.width();
 cfg.itemSize = cfg.itemSize || cfg.$items.outerWidth(true);
 }
 cfg.itemsVisible = Math.round(cfg.windowSize / cfg.itemSize);
 cfg.offscreenOffset = -cfg.itemSize;
 cfg.futureIndex = 0;
 cfg.futureDirection = 'next';
 cfg.isMoving = cfg.autoplay = false;
 this._setPositions();
 },

 _cfgPagination: function(cfg) {
 var cfgPagination = cfg.pagination;
 if(!isObject(cfgPagination)) {
 throw new Error('missing pagination config for carouselCircular');
 }
 if(!cfgPagination.$target) {
 cfgPagination.$target = cfg.$core;
 }
 cfgPagination.circular = true;
 cfgPagination.total = cfg.$items.length;
 if(!cfgPagination.perPage) {
 cfgPagination.perPage = cfg.itemsVisible;
 }
 if(!cfgPagination.listeners) {
 cfgPagination.listeners = require('events/listeners').create();
 }
 cfgPagination.listeners.add('change', this, '_handlePageChange');
 this.pagination = require('ui/pagination').create(cfg.pagination);
 },

 _cfgSequence: function() {
 this.changing = require('sequence/manager').create({ name: 'change carousel' })
 .describe('start change', {
 enter: function(data) {
 this.proceed(data.source.cfg.fx ? 'main' : 'end change');
 },
 main: function(data) {
 data.fx = data.source.cfg.fx.prepare(data.source);
 data.fx.play();
 },
 leave: function(data) {
 this.when(data.fx).emit('end').proceed('end change');
 }
 })
 .describe('end change', {
 main: function(data) {
 data.source._setPositions();
 data.source.dispatch('change');
 },
 leave: 'exit'
 })
 .freeze();
 },

 _setSelectedItem: function(cfg) {
 var itemSelectedClass = cfg.itemSelectedClass;
 var $items = cfg.$items;
 var i = -1;
 var l = $items.length;
 while(++i < l) {
 if($items.eq(i).hasClass(itemSelectedClass)) {
 this.nth(Math.ceil((i + 1) / this.pagination.cfg.perPage));
 break;
 }
 }
 },

 _normalizeIndex: function(index) {
 var total = this.cfg.$items.length;
 var modulo = index % total;
 return modulo < 0 ? modulo + total : modulo;
 },

 _setPositions: function() {
 var cfg = this.cfg;
 var cssProperty = cfg.cssProperty;
 var itemSize = cfg.itemSize;
 var offscreenOffset = cfg.offscreenOffset;
 var futureIndex = cfg.futureIndex;
 var i = futureIndex;
 var l = i + cfg.$items.length;
 var limit = i + cfg.itemsVisible;
 var count = 0;
 var j;
 cfg.$movable[0].style[cssProperty] = '0px';
 while(i < l) {
 j = this._normalizeIndex(i);
 cfg.$items[j].style[cssProperty] = (i < limit ? (count * itemSize) : offscreenOffset) + 'px';
 count++;
 i++;
 }
 cfg.currentIndex = futureIndex;
 },

 _getFutureDirection: function(action, currentIndex, futureIndex) {
 if(action === 'next' || action === 'last') {
 return 'next';
 }
 if(action === 'previous' || action === 'first') {
 return 'previous';
 }
 return futureIndex > currentIndex ? 'next' : 'previous';
 },

 _handlePageChange: function(evt) {
 var cfg = this.cfg;
 var cfgPagination = evt.source.cfg;
 var futureIndex = cfgPagination.perPage * (cfgPagination.current - 1);
 if(futureIndex !== cfg.currentIndex && !this.changing.cfg.isPlaying) {
 cfg.futureIndex = futureIndex;
 cfg.futureDirection = this._getFutureDirection(evt.details.action, cfg.currentIndex, cfg.futureIndex);
 this.changing.play({ source: this });
 }
 },

 nth: function(page) {
 this.pagination.nth(page);
 },

 previous: function() {
 this.pagination.previous();
 },

 next: function() {
 this.pagination.next();
 },

 first: function() {
 this.pagination.first();
 },

 last: function() {
 this.pagination.last();
 }
 });
 });

 box.define('ui/tabs', function(require, module) {
 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 rootClass: 'tabs',
 listClass: 'tabs-list',
 listItemClass: 'tab',
 listItemSelectedClass: 'tab-selected',
 allPanelsClass: 'tab-all-panels',
 panelClass: 'tab-panel',
 panelSelectedClass: 'tab-panel-selected',
 listHtml: '<ul class="tabs-list">{$items}</ul>',
 listItemHtml: '<li class="tab"><a href="#{$id}">{$label}</a></li>',
 titleSelector: 'h1,h2,h3,h4,h5,h6',

 reId: /\{\$id\}/,
 reLabel: /\{\$label\}/,
 reItems: /\{\$items\}/
 },

 setup: function(cfg) {
 this.callModule('events/target@setup', cfg);
 this._cfgElements(cfg);
 },

 _cfgElements: function(cfg) {
 var $core = cfg.$core;
 if(!$core || !$core.jquery || !$core.length || !$core.hasClass(cfg.rootClass)) {
 throw new Error('missing or invalid core element for tabs');
 }

 if($core.find('.' + cfg.listClass).length === 0) {
 this._createTabsList(cfg);
 }

 cfg.delegateId = require('dom/evtDelegateManager').registerModule(this);
 var elmData = require('dom/elmData');
 var delegateId = cfg.delegateId + '@_handleTabClick';
 $core.find('.' + cfg.listItemClass + ' a').each(function(i, elm) {
 elmData.set(elm, 'onclick', delegateId);
 });

 var $selected = $core.find('.' + cfg.listItemSelectedClass);
 if(!$selected.length) {
 $selected = $core.find('.' + cfg.listItemClass).eq(0).addClass(cfg.listItemSelectedClass);
 } else if(!$j($selected.find('a').attr('href')).hasClass(cfg.panelSelectedClass)) {
 throw new Error('a tab is selected but its corresponding panel is not');
 }
 cfg.$selected = $selected;

 $j($selected.find('a').attr('href')).addClass(cfg.panelSelectedClass);
 },

 _createTabsList: function(cfg) {
 var listItemHtml = cfg.listItemHtml;
 var titleSelector = cfg.titleSelector;
 var $panels = cfg.$core.find('.' + cfg.panelClass);
 var i = -1;
 var l = $panels.length;
 var itemsHtml = '';
 var $panel, title;
 while(++i < l) {
 $panel = $panels.eq(i);
 title = $panel.find(titleSelector).eq(0).text();
 itemsHtml += listItemHtml.replace(cfg.reId, $panel.attr('id')).replace(cfg.reLabel, title);
 }
 $j(cfg.listHtml.replace(cfg.reItems, itemsHtml)).prependTo(cfg.$core);
 },

 select: function(id) {
 var cfg = this.cfg;
 var listItemSelectedClass = cfg.listItemSelectedClass;
 var panelSelectedClass = cfg.panelSelectedClass;
 var type = typeof id;
 var $futureSelected, panelId;
 if(type === 'string') {
 $futureSelected = cfg.$core.find('.' + cfg.listItemClass + ' a[href="#' + id + '"]').parent();
 panelId = '#' + id;
 } else if(type === 'number') {
 $futureSelected = cfg.$core.find('.' + cfg.listItemClass).eq(id);
 panelId = $futureSelected.find('a').attr('href');
 }
 if($futureSelected.length && $futureSelected[0] !== cfg.$selected[0]) {
 $j(cfg.$selected.find('a').attr('href')).removeClass(panelSelectedClass);
 $j(panelId).addClass(panelSelectedClass);
 cfg.$selected.removeClass(listItemSelectedClass);
 cfg.$selected = $futureSelected.addClass(listItemSelectedClass);
 this.dispatch('change');
 }
 },

 _handleTabClick: function(data) {
 data.event.preventDefault();
 var id = data.element.getAttribute('href').substring(1);
 this.select(id);
 }
 });
 });

 box.define('ui/popinOpeningSequence', function(require, module) {
 var sequence = require('sequence/manager');

 module.exports = {
 create: function() {
 var opening = sequence.create({ name: 'popinOpening' });

 function animate(data, target) {
 var cfg = data.source.cfg;
 var name = target + 'OpenFx';
 cfg[name].$elm = data[target].cfg.$core;
 data[name] = require('fx/css').create(cfg[name]);
 data[name].play();
 }

 opening
 .describe('start opening', {
 enter: function(data) {
 this.proceed(data.source.canOpenPopin(data.settings) ? 'main' : 'exit');
 },
 main: function(data) {
 var src = data.source;
 src.dataNext = data.settings;
 src.dispatch('beforeOpen');
 },
 leave: function(data) {
 this.proceed(data.settings.id ? 'search html' : 'fetch content');
 }
 })
 .describe('search html', {
 main: function(data) {
 var id = data.settings.id;
 var html = data.source.cfg.html;
 if(id in html) {
 data.html = html[id];
 } else {
 data.html = html.error;
 data.settings.error = { status: 'error', response: 'Id Not Found' };
 data.source.dataNext = null;
 data.source.dispatch('error', data.settings);
 }
 },
 leave: function(data) {
 this.proceed(data.html ? 'insert root' : 'exit');
 }
 })
 .describe('fetch content', {
 main: function(data) {
 data.service.send(data.settings);
 },
 leave: function(data) {
 this.when(data.service).emit('complete').proceed('service complete');
 this.proceed('insert root');
 }
 })
 .describe('service complete', {
 main: function(data) {
 var details = data.lastEvent.details.details;
 var html;
 if(details.status === 'success' || details.status === 'notmodified') {
 data.html = details.response;
 } else {
 html = data.source.cfg.html;
 data.html = html['error' + details.httpCode] || html.error;
 data.settings.error = { status: 'error', content: details.response };
 data.source.dispatch('error', data.settings);
 }
 },
 leave: function(data) {
 this.proceed(data.html ? 'insert popin' : 'exit');
 }
 })
 .describe('insert root', {
 main: function(data) {
 data.root.insert();
 },
 leave: 'insert mask'
 })
 .describe('insert mask', {
 enter: function(data) {
 this.proceed(data.mask.cfg.coreHtml ? 'main' : 'insert loader');
 },
 main: function(data) {
 data.mask.cfg.insertTarget = data.root.cfg.$core;
 data.mask.insert().cover(data.source.cfg.maskCoverRef);
 },
 leave: 'animate mask'
 })
 .describe('animate mask', {
 enter: function(data) {
 this.proceed(data.source.cfg.maskOpenFx ? 'main' : 'insert loader');
 },
 main: function(data) {
 animate(data, 'mask');
 },
 leave: function(data) {
 this.when(data.maskOpenFx).emit('end').proceed('insert loader');
 }
 })
 .describe('insert loader', {
 enter: function(data) {
 if(data.html) {
 data.isReadyForInsertion = true;
 this.proceed('insert popin');
 } else if(data.loader.cfg.coreHtml) {
 this.proceed('main');
 } else {
 data.isReadyForInsertion = true;
 this.proceed('insert popin');
 }
 },
 main: function(data) {
 var cfg = data.source.cfg;
 data.loader.cfg.insertTarget = data.root.cfg.$core;
 data.loader.insert();
 if(cfg.loaderPosition) {
 data.loader.position(cfg.loaderPositionRef, cfg.loaderPosition, { allowTargetBeforeRef: cfg.allowLoaderBeforeRef });
 }
 },
 leave: 'animate loader'
 })
 .describe('animate loader', {
 enter: function(data) {
 this.proceed(data.source.cfg.loaderOpenFx ? 'main' : 'dependancies ok');
 },
 main: function(data) {
 data.loader.cfg.$core.attr('tabindex', '-1').focus();
 animate(data, 'loader');
 },
 leave: function(data) {
 this.when(data.loaderOpenFx).emit('end').proceed('dependancies ok');
 }
 })
 .describe('dependancies ok', {
 main: function(data) {
 data.isReadyForInsertion = true;
 },
 leave: 'insert popin'
 })
 .describe('insert popin', {
 enter: function(data) {
 this.proceed(data.isReadyForInsertion && data.html ? 'main' : 'wait');
 },
 main: function(data) {
 var cfg = data.source.cfg;
 data.popin.cfg.insertTarget = data.root.cfg.$core;
 data.popin.replace(data.html).insert();
 if(cfg.popinPosition) {
 data.popin.position(cfg.popinPositionRef, cfg.popinPosition, { allowTargetBeforeRef: cfg.allowPopinBeforeRef });
 }
 data.mask.cover(cfg.maskCoverRef);
 require('dom/elmComponents').setup(data.root.cfg.$core);
 data.source.data = data.settings;
 data.source.dataNext = null;
 },
 leave: 'animate popin'
 })
 .describe('animate popin', {
 enter: function(data) {
 this.proceed(data.source.cfg.popinOpenFx ? 'main' : 'end opening');
 },
 main: function(data) {
 animate(data, 'popin');
 },
 leave: 'end opening'
 })
 .describe('end opening', {
 main: function(data) {
 data.popin.cfg.$core.attr('tabindex', '-1').focus();
 data.source.dispatch('open');
 },
 leave: 'exit'
 });

 return opening.freeze();
 }
 };
 });

 box.define('ui/popinClosingSequence', function(require, module) {
 var sequence = require('sequence/manager');

 module.exports = {
 create: function() {
 var closing = sequence.create({ name: 'popinClosing' });

 function animate(data, target) {
 var cfg = data.source.cfg;
 var name = target + 'CloseFx';
 cfg[name].$elm = data[target].cfg.$core;
 data[name] = require('fx/css').create(cfg[name]);
 data[name].play();
 }

 closing
 .describe('start closing', {
 main: function(data) {
 data.source.dispatch('beforeClose');
 require('dom/elmComponents').cleanup(data.root.cfg.$core);
 data.loader.remove();
 },
 leave: 'animate popin'
 })
 .describe('animate popin', {
 enter: function(data) {
 this.proceed(data.source.cfg.popinCloseFx ? 'main' : 'animate mask');
 },
 main: function(data) {
 animate(data, 'popin');
 },
 leave: function(data) {
 this.when(data.popinCloseFx).emit('end').proceed('animate mask');
 }
 })
 .describe('animate mask', {
 enter: function(data) {
 this.proceed(data.source.cfg.maskCloseFx ? 'main' : 'end closing');
 },
 main: function(data) {
 animate(data, 'mask');
 },
 leave: function(data) {
 this.when(data.maskCloseFx).emit('end').proceed('end closing');
 }
 })
 .describe('end closing', {
 main: function(data) {
 data.popin.remove();
 data.mask.remove();
 data.root.remove();
 var popinData = data.source.data;
 if(popinData && popinData.element) {
 popinData.element.focus();
 }
 data.source.dispatch('close');
 },
 leave: 'exit'
 });

 return closing.freeze();
 }
 };
 });

 box.define('ui/popin', function(require, module) {
 var element = require('ui/element');

 module.exports = require('component/factory').create({
 MIXINS: [ 'events/target' ],

 DEFAULTS: {
 rootHtml: '<div class="popin-wrapper"></div>',
 maskHtml: '<div class="popin-mask"></div>',
 loaderHtml: '<div class="popin-loader"></div>',

 insertTarget: 'body',
 insertMethod: 'appendTo',

 popinPositionRef: 'viewport',
 popinPosition: 'cc-cc',
 maskCoverRef: 'document',
 loaderPositionRef: 'viewport',
 loaderPosition: 'cc-cc',
 allowPopinBeforeRef: false,
 allowLoaderBeforeRef: false
 },

 setup: function(cfg) {
 var isObject = require('lang/isObject');

 if(!isObject(cfg.html)) {
 cfg.html = {};
 }

 this._cfgElements(cfg);
 this._cfgSequences();

 this.callModule('events/target@setup', cfg);
 },

 cleanup: function() {
 if(this.cfg.service) {
 this.cfg.service.off('*', this);
 }
 this.close();
 },

 _cfgElements: function(cfg) {
 this.root = element.create({
 coreHtml: cfg.rootHtml,
 insertTarget: cfg.insertTarget,
 insertMethod: cfg.insertMethod
 });
 this.mask = element.create({
 coreHtml: cfg.maskHtml
 });
 this.loader = element.create({
 coreHtml: cfg.loaderHtml,
 allowTargetBeforeRef: cfg.allowLoaderBeforeRef
 });
 this.popin = element.create({
 allowTargetBeforeRef: cfg.allowPopinBeforeRef
 });
 },

 _cfgSequences: function() {
 this.opening = require('ui/popinOpeningSequence').create();
 this.closing = require('ui/popinClosingSequence').create();
 },

 canOpenPopin: function(settings) {
 return !this.opening.isPlaying && !this.closing.isPlaying && !this.root.cfg.isInDom && !!(settings.id || settings.url);
 },

 canClosePopin: function() {
 return !this.opening.isPlaying && !this.closing.isPlaying && this.popin.cfg.isInDom;
 },

 open: function(settings) {
 if(this.canOpenPopin(settings)) {
 this.opening.play({
 source: this,
 settings: settings,
 service: this.cfg.service,
 root: this.root,
 mask: this.mask,
 loader: this.loader,
 popin: this.popin
 });
 }
 },

 close: function() {
 if(this.canClosePopin()) {
 this.closing.play({
 source: this,
 root: this.root,
 mask: this.mask,
 loader: this.loader,
 popin: this.popin
 });
 }
 },

 _handleClick: function(data) {
 data.event.preventDefault();
 var href = data.element.getAttribute('href', 2);
 if(href) {
 if(href.charAt(0) === '#') {
 data.id = href.substring(1);
 } else {
 data.url = href;
 }
 }
 if(data.id || data.url) {
 this.open(data);
 } else {
 this.close();
 }
 }
 });
 });