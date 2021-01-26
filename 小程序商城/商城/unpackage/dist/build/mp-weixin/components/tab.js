(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/tab"],{"78eb":function(e,t,n){"use strict";n.r(t);var r=n("958e"),a=n("9a92");for(var u in a)"default"!==u&&function(e){n.d(t,e,(function(){return a[e]}))}(u);var o,c=n("f0c5"),i=Object(c["a"])(a["default"],r["b"],r["c"],!1,null,null,null,!1,r["a"],o);t["default"]=i.exports},"93bc":function(e,t,n){"use strict";(function(e){Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n={name:"tab",data:function(){return{}},props:{active:{type:Number,value:1}},onLoad:function(){},methods:{onChange:function(t){0==t.detail?e.redirectTo({url:"../index/index"}):1==t.detail?e.redirectTo({url:"../store/index"}):2==t.detail?e.redirectTo({url:"../shop_car/index"}):3==t.detail&&e.redirectTo({url:"../my/index"}),console.log(t.detail),console.log(this.active)}},created:function(){}};t.default=n}).call(this,n("543d")["default"])},"958e":function(e,t,n){"use strict";var r;n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return u})),n.d(t,"a",(function(){return r}));var a=function(){var e=this,t=e.$createElement;e._self._c},u=[]},"9a92":function(e,t,n){"use strict";n.r(t);var r=n("93bc"),a=n.n(r);for(var u in r)"default"!==u&&function(e){n.d(t,e,(function(){return r[e]}))}(u);t["default"]=a.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/tab-create-component',
    {
        'components/tab-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("78eb"))
        })
    },
    [['components/tab-create-component']]
]);
