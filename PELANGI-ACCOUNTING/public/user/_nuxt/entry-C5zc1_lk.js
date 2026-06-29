const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["./index-BKzPDcje.js","./Button-NLMKkPll.js","./virtual_public-CVwdIIwa.js","./iconify-UvQ5sOGw.js","./forgot-BajuqLfH.js","./virtual_public-MkqJ4Lbi.js","./index-Dk2vUOhB.js","./reset-DSL6HENl.js","./_plugin-vue_export-helper-DlAUqK2U.js","./verify-COF2ROIM.js","./faq-DIfNe5Sa.js","./Header-C9st8ncM.js","./home-BUJmxL0Q.js","./Segment-DfeaJLx4.js","./Status-H_jUSNHz.js","./index-DWsOfGCw.js","./_commonjsHelpers-Cpj98o6Y.js","./store-CMdEv2vv.js","./home-B_X22sgy.css","./_id_-C8cZMSFE.js","./capture-DTTns17F.js","./dayjs.min-DMGOSre-.js","./index-fH9qp2FD.js","./vue-datepicker-tr4Kqdw3.js","./notifications-DT7U4xw0.js","./_id_-CKw8sDIf.js","./index-CWyZnB_2.js","./InputTime-BEtHG7OD.js","./InputTime-B0iYlHqg.css","./InputLongText-DvncXLca.js","./list-NpYGgzD-.js","./_id_-B-e0_Gej.js","./done-B7oAxAZB.js","./index-BRxfCamj.js","./index-CVvk_6f2.css","./manual copy-BIUL7_Bg.js","./error-404-BBoYjVzZ.js","./error-404-ygbHJO5Q.css","./error-500-DrXx-sSJ.js","./error-500-B11Ibp8J.css"])))=>i.map(i=>d[i]);
/**
* @vue/shared v3.5.8
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**//*! #__NO_SIDE_EFFECTS__ */function Gh(t){const e=Object.create(null);for(const n of t.split(","))e[n]=1;return n=>n in e}const Te={},qi=[],Nn=()=>{},MT=()=>!1,Po=t=>t.charCodeAt(0)===111&&t.charCodeAt(1)===110&&(t.charCodeAt(2)>122||t.charCodeAt(2)<97),Yh=t=>t.startsWith("onUpdate:"),Ze=Object.assign,Xh=(t,e)=>{const n=t.indexOf(e);n>-1&&t.splice(n,1)},FT=Object.prototype.hasOwnProperty,Ce=(t,e)=>FT.call(t,e),re=Array.isArray,zi=t=>Oo(t)==="[object Map]",q_=t=>Oo(t)==="[object Set]",UT=t=>Oo(t)==="[object RegExp]",ce=t=>typeof t=="function",He=t=>typeof t=="string",Us=t=>typeof t=="symbol",Le=t=>t!==null&&typeof t=="object",z_=t=>(Le(t)||ce(t))&&ce(t.then)&&ce(t.catch),G_=Object.prototype.toString,Oo=t=>G_.call(t),$T=t=>Oo(t).slice(8,-1),Y_=t=>Oo(t)==="[object Object]",Jh=t=>He(t)&&t!=="NaN"&&t[0]!=="-"&&""+parseInt(t,10)===t,Gi=Gh(",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"),Vl=t=>{const e=Object.create(null);return n=>e[n]||(e[n]=t(n))},HT=/-(\w)/g,un=Vl(t=>t.replace(HT,(e,n)=>n?n.toUpperCase():"")),BT=/\B([A-Z])/g,as=Vl(t=>t.replace(BT,"-$1").toLowerCase()),Wl=Vl(t=>t.charAt(0).toUpperCase()+t.slice(1)),jc=Vl(t=>t?`on${Wl(t)}`:""),Ht=(t,e)=>!Object.is(t,e),Yi=(t,...e)=>{for(let n=0;n<t.length;n++)t[n](...e)},X_=(t,e,n,s=!1)=>{Object.defineProperty(t,e,{configurable:!0,enumerable:!1,writable:s,value:n})},Lu=t=>{const e=parseFloat(t);return isNaN(e)?t:e},J_=t=>{const e=He(t)?Number(t):NaN;return isNaN(e)?t:e};let Cp;const Q_=()=>Cp||(Cp=typeof globalThis<"u"?globalThis:typeof self<"u"?self:typeof window<"u"?window:typeof global<"u"?global:{});function Kl(t){if(re(t)){const e={};for(let n=0;n<t.length;n++){const s=t[n],i=He(s)?KT(s):Kl(s);if(i)for(const r in i)e[r]=i[r]}return e}else if(He(t)||Le(t))return t}const jT=/;(?![^(]*\))/g,VT=/:([^]+)/,WT=/\/\*[^]*?\*\//g;function KT(t){const e={};return t.replace(WT,"").split(jT).forEach(n=>{if(n){const s=n.split(VT);s.length>1&&(e[s[0].trim()]=s[1].trim())}}),e}function ql(t){let e="";if(He(t))e=t;else if(re(t))for(let n=0;n<t.length;n++){const s=ql(t[n]);s&&(e+=s+" ")}else if(Le(t))for(const n in t)t[n]&&(e+=n+" ");return e.trim()}function qT(t){if(!t)return null;let{class:e,style:n}=t;return e&&!He(e)&&(t.class=ql(e)),n&&(t.style=Kl(n)),t}const zT="itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly",GT=Gh(zT);function Z_(t){return!!t||t===""}const ey=t=>!!(t&&t.__v_isRef===!0),YT=t=>He(t)?t:t==null?"":re(t)||Le(t)&&(t.toString===G_||!ce(t.toString))?ey(t)?YT(t.value):JSON.stringify(t,ty,2):String(t),ty=(t,e)=>ey(e)?ty(t,e.value):zi(e)?{[`Map(${e.size})`]:[...e.entries()].reduce((n,[s,i],r)=>(n[Vc(s,r)+" =>"]=i,n),{})}:q_(e)?{[`Set(${e.size})`]:[...e.values()].map(n=>Vc(n))}:Us(e)?Vc(e):Le(e)&&!re(e)&&!Y_(e)?String(e):e,Vc=(t,e="")=>{var n;return Us(t)?`Symbol(${(n=t.description)!=null?n:e})`:t};/**
* @vue/reactivity v3.5.8
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/let xt;class ny{constructor(e=!1){this.detached=e,this._active=!0,this.effects=[],this.cleanups=[],this._isPaused=!1,this.parent=xt,!e&&xt&&(this.index=(xt.scopes||(xt.scopes=[])).push(this)-1)}get active(){return this._active}pause(){if(this._active){this._isPaused=!0;let e,n;if(this.scopes)for(e=0,n=this.scopes.length;e<n;e++)this.scopes[e].pause();for(e=0,n=this.effects.length;e<n;e++)this.effects[e].pause()}}resume(){if(this._active&&this._isPaused){this._isPaused=!1;let e,n;if(this.scopes)for(e=0,n=this.scopes.length;e<n;e++)this.scopes[e].resume();for(e=0,n=this.effects.length;e<n;e++)this.effects[e].resume()}}run(e){if(this._active){const n=xt;try{return xt=this,e()}finally{xt=n}}}on(){xt=this}off(){xt=this.parent}stop(e){if(this._active){let n,s;for(n=0,s=this.effects.length;n<s;n++)this.effects[n].stop();for(n=0,s=this.cleanups.length;n<s;n++)this.cleanups[n]();if(this.scopes)for(n=0,s=this.scopes.length;n<s;n++)this.scopes[n].stop(!0);if(!this.detached&&this.parent&&!e){const i=this.parent.scopes.pop();i&&i!==this&&(this.parent.scopes[this.index]=i,i.index=this.index)}this.parent=void 0,this._active=!1}}}function sy(t){return new ny(t)}function iy(){return xt}function Y4(t,e=!1){xt&&xt.cleanups.push(t)}let xe;const Wc=new WeakSet;class ry{constructor(e){this.fn=e,this.deps=void 0,this.depsTail=void 0,this.flags=5,this.next=void 0,this.cleanup=void 0,this.scheduler=void 0,xt&&xt.active&&xt.effects.push(this)}pause(){this.flags|=64}resume(){this.flags&64&&(this.flags&=-65,Wc.has(this)&&(Wc.delete(this),this.trigger()))}notify(){this.flags&2&&!(this.flags&32)||this.flags&8||ay(this)}run(){if(!(this.flags&1))return this.fn();this.flags|=2,Ip(this),ly(this);const e=xe,n=vn;xe=this,vn=!0;try{return this.fn()}finally{cy(this),xe=e,vn=n,this.flags&=-3}}stop(){if(this.flags&1){for(let e=this.deps;e;e=e.nextDep)ed(e);this.deps=this.depsTail=void 0,Ip(this),this.onStop&&this.onStop(),this.flags&=-2}}trigger(){this.flags&64?Wc.add(this):this.scheduler?this.scheduler():this.runIfDirty()}runIfDirty(){Mu(this)&&this.run()}get dirty(){return Mu(this)}}let oy=0,Xr;function ay(t){t.flags|=8,t.next=Xr,Xr=t}function Qh(){oy++}function Zh(){if(--oy>0)return;let t;for(;Xr;){let e=Xr;for(Xr=void 0;e;){const n=e.next;if(e.next=void 0,e.flags&=-9,e.flags&1)try{e.trigger()}catch(s){t||(t=s)}e=n}}if(t)throw t}function ly(t){for(let e=t.deps;e;e=e.nextDep)e.version=-1,e.prevActiveLink=e.dep.activeLink,e.dep.activeLink=e}function cy(t,e=!1){let n,s=t.depsTail,i=s;for(;i;){const r=i.prevDep;i.version===-1?(i===s&&(s=r),ed(i,e),XT(i)):n=i,i.dep.activeLink=i.prevActiveLink,i.prevActiveLink=void 0,i=r}t.deps=n,t.depsTail=s}function Mu(t){for(let e=t.deps;e;e=e.nextDep)if(e.dep.version!==e.version||e.dep.computed&&(uy(e.dep.computed)||e.dep.version!==e.version))return!0;return!!t._dirty}function uy(t){if(t.flags&4&&!(t.flags&16)||(t.flags&=-17,t.globalVersion===ho))return;t.globalVersion=ho;const e=t.dep;if(t.flags|=2,e.version>0&&!t.isSSR&&t.deps&&!Mu(t)){t.flags&=-3;return}const n=xe,s=vn;xe=t,vn=!0;try{ly(t);const i=t.fn(t._value);(e.version===0||Ht(i,t._value))&&(t._value=i,e.version++)}catch(i){throw e.version++,i}finally{xe=n,vn=s,cy(t,!0),t.flags&=-3}}function ed(t,e=!1){const{dep:n,prevSub:s,nextSub:i}=t;if(s&&(s.nextSub=i,t.prevSub=void 0),i&&(i.prevSub=s,t.nextSub=void 0),n.subs===t&&(n.subs=s),!n.subs)if(n.computed){n.computed.flags&=-5;for(let r=n.computed.deps;r;r=r.nextDep)ed(r,!0)}else n.map&&!e&&(n.map.delete(n.key),n.map.size||fo.delete(n.target))}function XT(t){const{prevDep:e,nextDep:n}=t;e&&(e.nextDep=n,t.prevDep=void 0),n&&(n.prevDep=e,t.nextDep=void 0)}let vn=!0;const hy=[];function $s(){hy.push(vn),vn=!1}function Hs(){const t=hy.pop();vn=t===void 0?!0:t}function Ip(t){const{cleanup:e}=t;if(t.cleanup=void 0,e){const n=xe;xe=void 0;try{e()}finally{xe=n}}}let ho=0;class JT{constructor(e,n){this.sub=e,this.dep=n,this.version=n.version,this.nextDep=this.prevDep=this.nextSub=this.prevSub=this.prevActiveLink=void 0}}class zl{constructor(e){this.computed=e,this.version=0,this.activeLink=void 0,this.subs=void 0,this.target=void 0,this.map=void 0,this.key=void 0}track(e){if(!xe||!vn||xe===this.computed)return;let n=this.activeLink;if(n===void 0||n.sub!==xe)n=this.activeLink=new JT(xe,this),xe.deps?(n.prevDep=xe.depsTail,xe.depsTail.nextDep=n,xe.depsTail=n):xe.deps=xe.depsTail=n,xe.flags&4&&dy(n);else if(n.version===-1&&(n.version=this.version,n.nextDep)){const s=n.nextDep;s.prevDep=n.prevDep,n.prevDep&&(n.prevDep.nextDep=s),n.prevDep=xe.depsTail,n.nextDep=void 0,xe.depsTail.nextDep=n,xe.depsTail=n,xe.deps===n&&(xe.deps=s)}return n}trigger(e){this.version++,ho++,this.notify(e)}notify(e){Qh();try{for(let n=this.subs;n;n=n.prevSub)n.sub.notify()&&n.sub.dep.notify()}finally{Zh()}}}function dy(t){const e=t.dep.computed;if(e&&!t.dep.subs){e.flags|=20;for(let s=e.deps;s;s=s.nextDep)dy(s)}const n=t.dep.subs;n!==t&&(t.prevSub=n,n&&(n.nextSub=t)),t.dep.subs=t}const fo=new WeakMap,ii=Symbol(""),Fu=Symbol(""),po=Symbol("");function At(t,e,n){if(vn&&xe){let s=fo.get(t);s||fo.set(t,s=new Map);let i=s.get(n);i||(s.set(n,i=new zl),i.target=t,i.map=s,i.key=n),i.track()}}function Zn(t,e,n,s,i,r){const o=fo.get(t);if(!o){ho++;return}const l=c=>{c&&c.trigger()};if(Qh(),e==="clear")o.forEach(l);else{const c=re(t),u=c&&Jh(n);if(c&&n==="length"){const h=Number(s);o.forEach((f,g)=>{(g==="length"||g===po||!Us(g)&&g>=h)&&l(f)})}else switch(n!==void 0&&l(o.get(n)),u&&l(o.get(po)),e){case"add":c?u&&l(o.get("length")):(l(o.get(ii)),zi(t)&&l(o.get(Fu)));break;case"delete":c||(l(o.get(ii)),zi(t)&&l(o.get(Fu)));break;case"set":zi(t)&&l(o.get(ii));break}}Zh()}function QT(t,e){var n;return(n=fo.get(t))==null?void 0:n.get(e)}function Li(t){const e=be(t);return e===t?e:(At(e,"iterate",po),cn(t)?e:e.map(bt))}function Gl(t){return At(t=be(t),"iterate",po),t}const ZT={__proto__:null,[Symbol.iterator](){return Kc(this,Symbol.iterator,bt)},concat(...t){return Li(this).concat(...t.map(e=>re(e)?Li(e):e))},entries(){return Kc(this,"entries",t=>(t[1]=bt(t[1]),t))},every(t,e){return jn(this,"every",t,e,void 0,arguments)},filter(t,e){return jn(this,"filter",t,e,n=>n.map(bt),arguments)},find(t,e){return jn(this,"find",t,e,bt,arguments)},findIndex(t,e){return jn(this,"findIndex",t,e,void 0,arguments)},findLast(t,e){return jn(this,"findLast",t,e,bt,arguments)},findLastIndex(t,e){return jn(this,"findLastIndex",t,e,void 0,arguments)},forEach(t,e){return jn(this,"forEach",t,e,void 0,arguments)},includes(...t){return qc(this,"includes",t)},indexOf(...t){return qc(this,"indexOf",t)},join(t){return Li(this).join(t)},lastIndexOf(...t){return qc(this,"lastIndexOf",t)},map(t,e){return jn(this,"map",t,e,void 0,arguments)},pop(){return $r(this,"pop")},push(...t){return $r(this,"push",t)},reduce(t,...e){return Sp(this,"reduce",t,e)},reduceRight(t,...e){return Sp(this,"reduceRight",t,e)},shift(){return $r(this,"shift")},some(t,e){return jn(this,"some",t,e,void 0,arguments)},splice(...t){return $r(this,"splice",t)},toReversed(){return Li(this).toReversed()},toSorted(t){return Li(this).toSorted(t)},toSpliced(...t){return Li(this).toSpliced(...t)},unshift(...t){return $r(this,"unshift",t)},values(){return Kc(this,"values",bt)}};function Kc(t,e,n){const s=Gl(t),i=s[e]();return s!==t&&!cn(t)&&(i._next=i.next,i.next=()=>{const r=i._next();return r.value&&(r.value=n(r.value)),r}),i}const eC=Array.prototype;function jn(t,e,n,s,i,r){const o=Gl(t),l=o!==t&&!cn(t),c=o[e];if(c!==eC[e]){const f=c.apply(t,r);return l?bt(f):f}let u=n;o!==t&&(l?u=function(f,g){return n.call(this,bt(f),g,t)}:n.length>2&&(u=function(f,g){return n.call(this,f,g,t)}));const h=c.call(o,u,s);return l&&i?i(h):h}function Sp(t,e,n,s){const i=Gl(t);let r=n;return i!==t&&(cn(t)?n.length>3&&(r=function(o,l,c){return n.call(this,o,l,c,t)}):r=function(o,l,c){return n.call(this,o,bt(l),c,t)}),i[e](r,...s)}function qc(t,e,n){const s=be(t);At(s,"iterate",po);const i=s[e](...n);return(i===-1||i===!1)&&id(n[0])?(n[0]=be(n[0]),s[e](...n)):i}function $r(t,e,n=[]){$s(),Qh();const s=be(t)[e].apply(t,n);return Zh(),Hs(),s}const tC=Gh("__proto__,__v_isRef,__isVue"),fy=new Set(Object.getOwnPropertyNames(Symbol).filter(t=>t!=="arguments"&&t!=="caller").map(t=>Symbol[t]).filter(Us));function nC(t){Us(t)||(t=String(t));const e=be(this);return At(e,"has",t),e.hasOwnProperty(t)}class py{constructor(e=!1,n=!1){this._isReadonly=e,this._isShallow=n}get(e,n,s){const i=this._isReadonly,r=this._isShallow;if(n==="__v_isReactive")return!i;if(n==="__v_isReadonly")return i;if(n==="__v_isShallow")return r;if(n==="__v_raw")return s===(i?r?gC:yy:r?_y:my).get(e)||Object.getPrototypeOf(e)===Object.getPrototypeOf(s)?e:void 0;const o=re(e);if(!i){let c;if(o&&(c=ZT[n]))return c;if(n==="hasOwnProperty")return nC}const l=Reflect.get(e,n,ut(e)?e:s);return(Us(n)?fy.has(n):tC(n))||(i||At(e,"get",n),r)?l:ut(l)?o&&Jh(n)?l:l.value:Le(l)?i?wy(l):In(l):l}}class gy extends py{constructor(e=!1){super(!1,e)}set(e,n,s,i){let r=e[n];if(!this._isShallow){const c=Ms(r);if(!cn(s)&&!Ms(s)&&(r=be(r),s=be(s)),!re(e)&&ut(r)&&!ut(s))return c?!1:(r.value=s,!0)}const o=re(e)&&Jh(n)?Number(n)<e.length:Ce(e,n),l=Reflect.set(e,n,s,ut(e)?e:i);return e===be(i)&&(o?Ht(s,r)&&Zn(e,"set",n,s):Zn(e,"add",n,s)),l}deleteProperty(e,n){const s=Ce(e,n);e[n];const i=Reflect.deleteProperty(e,n);return i&&s&&Zn(e,"delete",n,void 0),i}has(e,n){const s=Reflect.has(e,n);return(!Us(n)||!fy.has(n))&&At(e,"has",n),s}ownKeys(e){return At(e,"iterate",re(e)?"length":ii),Reflect.ownKeys(e)}}class sC extends py{constructor(e=!1){super(!0,e)}set(e,n){return!0}deleteProperty(e,n){return!0}}const iC=new gy,rC=new sC,oC=new gy(!0);const td=t=>t,Yl=t=>Reflect.getPrototypeOf(t);function ga(t,e,n=!1,s=!1){t=t.__v_raw;const i=be(t),r=be(e);n||(Ht(e,r)&&At(i,"get",e),At(i,"get",r));const{has:o}=Yl(i),l=s?td:n?rd:bt;if(o.call(i,e))return l(t.get(e));if(o.call(i,r))return l(t.get(r));t!==i&&t.get(e)}function ma(t,e=!1){const n=this.__v_raw,s=be(n),i=be(t);return e||(Ht(t,i)&&At(s,"has",t),At(s,"has",i)),t===i?n.has(t):n.has(t)||n.has(i)}function _a(t,e=!1){return t=t.__v_raw,!e&&At(be(t),"iterate",ii),Reflect.get(t,"size",t)}function Ap(t,e=!1){!e&&!cn(t)&&!Ms(t)&&(t=be(t));const n=be(this);return Yl(n).has.call(n,t)||(n.add(t),Zn(n,"add",t,t)),this}function kp(t,e,n=!1){!n&&!cn(e)&&!Ms(e)&&(e=be(e));const s=be(this),{has:i,get:r}=Yl(s);let o=i.call(s,t);o||(t=be(t),o=i.call(s,t));const l=r.call(s,t);return s.set(t,e),o?Ht(e,l)&&Zn(s,"set",t,e):Zn(s,"add",t,e),this}function Rp(t){const e=be(this),{has:n,get:s}=Yl(e);let i=n.call(e,t);i||(t=be(t),i=n.call(e,t)),s&&s.call(e,t);const r=e.delete(t);return i&&Zn(e,"delete",t,void 0),r}function Pp(){const t=be(this),e=t.size!==0,n=t.clear();return e&&Zn(t,"clear",void 0,void 0),n}function ya(t,e){return function(s,i){const r=this,o=r.__v_raw,l=be(o),c=e?td:t?rd:bt;return!t&&At(l,"iterate",ii),o.forEach((u,h)=>s.call(i,c(u),c(h),r))}}function wa(t,e,n){return function(...s){const i=this.__v_raw,r=be(i),o=zi(r),l=t==="entries"||t===Symbol.iterator&&o,c=t==="keys"&&o,u=i[t](...s),h=n?td:e?rd:bt;return!e&&At(r,"iterate",c?Fu:ii),{next(){const{value:f,done:g}=u.next();return g?{value:f,done:g}:{value:l?[h(f[0]),h(f[1])]:h(f),done:g}},[Symbol.iterator](){return this}}}}function ms(t){return function(...e){return t==="delete"?!1:t==="clear"?void 0:this}}function aC(){const t={get(r){return ga(this,r)},get size(){return _a(this)},has:ma,add:Ap,set:kp,delete:Rp,clear:Pp,forEach:ya(!1,!1)},e={get(r){return ga(this,r,!1,!0)},get size(){return _a(this)},has:ma,add(r){return Ap.call(this,r,!0)},set(r,o){return kp.call(this,r,o,!0)},delete:Rp,clear:Pp,forEach:ya(!1,!0)},n={get(r){return ga(this,r,!0)},get size(){return _a(this,!0)},has(r){return ma.call(this,r,!0)},add:ms("add"),set:ms("set"),delete:ms("delete"),clear:ms("clear"),forEach:ya(!0,!1)},s={get(r){return ga(this,r,!0,!0)},get size(){return _a(this,!0)},has(r){return ma.call(this,r,!0)},add:ms("add"),set:ms("set"),delete:ms("delete"),clear:ms("clear"),forEach:ya(!0,!0)};return["keys","values","entries",Symbol.iterator].forEach(r=>{t[r]=wa(r,!1,!1),n[r]=wa(r,!0,!1),e[r]=wa(r,!1,!0),s[r]=wa(r,!0,!0)}),[t,n,e,s]}const[lC,cC,uC,hC]=aC();function nd(t,e){const n=e?t?hC:uC:t?cC:lC;return(s,i,r)=>i==="__v_isReactive"?!t:i==="__v_isReadonly"?t:i==="__v_raw"?s:Reflect.get(Ce(n,i)&&i in s?n:s,i,r)}const dC={get:nd(!1,!1)},fC={get:nd(!1,!0)},pC={get:nd(!0,!1)};const my=new WeakMap,_y=new WeakMap,yy=new WeakMap,gC=new WeakMap;function mC(t){switch(t){case"Object":case"Array":return 1;case"Map":case"Set":case"WeakMap":case"WeakSet":return 2;default:return 0}}function _C(t){return t.__v_skip||!Object.isExtensible(t)?0:mC($T(t))}function In(t){return Ms(t)?t:sd(t,!1,iC,dC,my)}function Gn(t){return sd(t,!1,oC,fC,_y)}function wy(t){return sd(t,!0,rC,pC,yy)}function sd(t,e,n,s,i){if(!Le(t)||t.__v_raw&&!(e&&t.__v_isReactive))return t;const r=i.get(t);if(r)return r;const o=_C(t);if(o===0)return t;const l=new Proxy(t,o===2?s:n);return i.set(t,l),l}function ri(t){return Ms(t)?ri(t.__v_raw):!!(t&&t.__v_isReactive)}function Ms(t){return!!(t&&t.__v_isReadonly)}function cn(t){return!!(t&&t.__v_isShallow)}function id(t){return t?!!t.__v_raw:!1}function be(t){const e=t&&t.__v_raw;return e?be(e):t}function Uu(t){return!Ce(t,"__v_skip")&&Object.isExtensible(t)&&X_(t,"__v_skip",!0),t}const bt=t=>Le(t)?In(t):t,rd=t=>Le(t)?wy(t):t;function ut(t){return t?t.__v_isRef===!0:!1}function Ge(t){return vy(t,!1)}function go(t){return vy(t,!0)}function vy(t,e){return ut(t)?t:new yC(t,e)}class yC{constructor(e,n){this.dep=new zl,this.__v_isRef=!0,this.__v_isShallow=!1,this._rawValue=n?e:be(e),this._value=n?e:bt(e),this.__v_isShallow=n}get value(){return this.dep.track(),this._value}set value(e){const n=this._rawValue,s=this.__v_isShallow||cn(e)||Ms(e);e=s?e:be(e),Ht(e,n)&&(this._rawValue=e,this._value=s?e:bt(e),this.dep.trigger())}}function Pe(t){return ut(t)?t.value:t}const wC={get:(t,e,n)=>e==="__v_raw"?t:Pe(Reflect.get(t,e,n)),set:(t,e,n,s)=>{const i=t[e];return ut(i)&&!ut(n)?(i.value=n,!0):Reflect.set(t,e,n,s)}};function by(t){return ri(t)?t:new Proxy(t,wC)}class vC{constructor(e){this.__v_isRef=!0,this._value=void 0;const n=this.dep=new zl,{get:s,set:i}=e(n.track.bind(n),n.trigger.bind(n));this._get=s,this._set=i}get value(){return this._value=this._get()}set value(e){this._set(e)}}function bC(t){return new vC(t)}class EC{constructor(e,n,s){this._object=e,this._key=n,this._defaultValue=s,this.__v_isRef=!0,this._value=void 0}get value(){const e=this._object[this._key];return this._value=e===void 0?this._defaultValue:e}set value(e){this._object[this._key]=e}get dep(){return QT(be(this._object),this._key)}}class TC{constructor(e){this._getter=e,this.__v_isRef=!0,this.__v_isReadonly=!0,this._value=void 0}get value(){return this._value=this._getter()}}function CC(t,e,n){return ut(t)?t:ce(t)?new TC(t):Le(t)&&arguments.length>1?IC(t,e,n):Ge(t)}function IC(t,e,n){const s=t[e];return ut(s)?s:new EC(t,e,n)}class SC{constructor(e,n,s){this.fn=e,this.setter=n,this._value=void 0,this.dep=new zl(this),this.__v_isRef=!0,this.deps=void 0,this.depsTail=void 0,this.flags=16,this.globalVersion=ho-1,this.effect=this,this.__v_isReadonly=!n,this.isSSR=s}notify(){if(this.flags|=16,!(this.flags&8)&&xe!==this)return ay(this),!0}get value(){const e=this.dep.track();return uy(this),e&&(e.version=this.dep.version),this._value}set value(e){this.setter&&this.setter(e)}}function AC(t,e,n=!1){let s,i;return ce(t)?s=t:(s=t.get,i=t.set),new SC(s,i,n)}const va={},Ya=new WeakMap;let Qs;function kC(t,e=!1,n=Qs){if(n){let s=Ya.get(n);s||Ya.set(n,s=[]),s.push(t)}}function RC(t,e,n=Te){const{immediate:s,deep:i,once:r,scheduler:o,augmentJob:l,call:c}=n,u=R=>i?R:cn(R)||i===!1||i===0?qn(R,1):qn(R);let h,f,g,m,I=!1,P=!1;if(ut(t)?(f=()=>t.value,I=cn(t)):ri(t)?(f=()=>u(t),I=!0):re(t)?(P=!0,I=t.some(R=>ri(R)||cn(R)),f=()=>t.map(R=>{if(ut(R))return R.value;if(ri(R))return u(R);if(ce(R))return c?c(R,2):R()})):ce(t)?e?f=c?()=>c(t,2):t:f=()=>{if(g){$s();try{g()}finally{Hs()}}const R=Qs;Qs=h;try{return c?c(t,3,[m]):t(m)}finally{Qs=R}}:f=Nn,e&&i){const R=f,N=i===!0?1/0:i;f=()=>qn(R(),N)}const D=iy(),M=()=>{h.stop(),D&&Xh(D.effects,h)};if(r&&e){const R=e;e=(...N)=>{R(...N),M()}}let x=P?new Array(t.length).fill(va):va;const b=R=>{if(!(!(h.flags&1)||!h.dirty&&!R))if(e){const N=h.run();if(i||I||(P?N.some((F,T)=>Ht(F,x[T])):Ht(N,x))){g&&g();const F=Qs;Qs=h;try{const T=[N,x===va?void 0:P&&x[0]===va?[]:x,m];c?c(e,3,T):e(...T),x=N}finally{Qs=F}}}else h.run()};return l&&l(b),h=new ry(f),h.scheduler=o?()=>o(b,!1):b,m=R=>kC(R,!1,h),g=h.onStop=()=>{const R=Ya.get(h);if(R){if(c)c(R,4);else for(const N of R)N();Ya.delete(h)}},e?s?b(!0):x=h.run():o?o(b.bind(null,!0),!0):h.run(),M.pause=h.pause.bind(h),M.resume=h.resume.bind(h),M.stop=M,M}function qn(t,e=1/0,n){if(e<=0||!Le(t)||t.__v_skip||(n=n||new Set,n.has(t)))return t;if(n.add(t),e--,ut(t))qn(t.value,e,n);else if(re(t))for(let s=0;s<t.length;s++)qn(t[s],e,n);else if(q_(t)||zi(t))t.forEach(s=>{qn(s,e,n)});else if(Y_(t)){for(const s in t)qn(t[s],e,n);for(const s of Object.getOwnPropertySymbols(t))Object.prototype.propertyIsEnumerable.call(t,s)&&qn(t[s],e,n)}return t}/**
* @vue/runtime-core v3.5.8
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/function No(t,e,n,s){try{return s?t(...s):t()}catch(i){yr(i,e,n)}}function En(t,e,n,s){if(ce(t)){const i=No(t,e,n,s);return i&&z_(i)&&i.catch(r=>{yr(r,e,n)}),i}if(re(t)){const i=[];for(let r=0;r<t.length;r++)i.push(En(t[r],e,n,s));return i}}function yr(t,e,n,s=!0){const i=e?e.vnode:null,{errorHandler:r,throwUnhandledErrorInProduction:o}=e&&e.appContext.config||Te;if(e){let l=e.parent;const c=e.proxy,u=`https://vuejs.org/error-reference/#runtime-${n}`;for(;l;){const h=l.ec;if(h){for(let f=0;f<h.length;f++)if(h[f](t,c,u)===!1)return}l=l.parent}if(r){$s(),No(r,null,10,[t,c,u]),Hs();return}}PC(t,n,i,s,o)}function PC(t,e,n,s=!0,i=!1){if(i)throw t;console.error(t)}let mo=!1,$u=!1;const Dt=[];let kn=0;const Xi=[];let vs=null,Ui=0;const Ey=Promise.resolve();let od=null;function bi(t){const e=od||Ey;return t?e.then(this?t.bind(this):t):e}function OC(t){let e=mo?kn+1:0,n=Dt.length;for(;e<n;){const s=e+n>>>1,i=Dt[s],r=_o(i);r<t||r===t&&i.flags&2?e=s+1:n=s}return e}function ad(t){if(!(t.flags&1)){const e=_o(t),n=Dt[Dt.length-1];!n||!(t.flags&2)&&e>=_o(n)?Dt.push(t):Dt.splice(OC(e),0,t),t.flags|=1,Ty()}}function Ty(){!mo&&!$u&&($u=!0,od=Ey.then(Cy))}function Hu(t){re(t)?Xi.push(...t):vs&&t.id===-1?vs.splice(Ui+1,0,t):t.flags&1||(Xi.push(t),t.flags|=1),Ty()}function Op(t,e,n=mo?kn+1:0){for(;n<Dt.length;n++){const s=Dt[n];if(s&&s.flags&2){if(t&&s.id!==t.uid)continue;Dt.splice(n,1),n--,s.flags&4&&(s.flags&=-2),s(),s.flags&4||(s.flags&=-2)}}}function Xa(t){if(Xi.length){const e=[...new Set(Xi)].sort((n,s)=>_o(n)-_o(s));if(Xi.length=0,vs){vs.push(...e);return}for(vs=e,Ui=0;Ui<vs.length;Ui++){const n=vs[Ui];n.flags&4&&(n.flags&=-2),n.flags&8||n(),n.flags&=-2}vs=null,Ui=0}}const _o=t=>t.id==null?t.flags&2?-1:1/0:t.id;function Cy(t){$u=!1,mo=!0;try{for(kn=0;kn<Dt.length;kn++){const e=Dt[kn];e&&!(e.flags&8)&&(e.flags&4&&(e.flags&=-2),No(e,e.i,e.i?15:14),e.flags&4||(e.flags&=-2))}}finally{for(;kn<Dt.length;kn++){const e=Dt[kn];e&&(e.flags&=-2)}kn=0,Dt.length=0,Xa(),mo=!1,od=null,(Dt.length||Xi.length)&&Cy()}}let it=null,Iy=null;function Ja(t){const e=it;return it=t,Iy=t&&t.type.__scopeId||null,e}function ld(t,e=it,n){if(!e||t._n)return t;const s=(...i)=>{s._d&&Kp(-1);const r=Ja(e);let o;try{o=t(...i)}finally{Ja(r),s._d&&Kp(1)}return o};return s._n=!0,s._c=!0,s._d=!0,s}function X4(t,e){if(it===null)return t;const n=tc(it),s=t.dirs||(t.dirs=[]);for(let i=0;i<e.length;i++){let[r,o,l,c=Te]=e[i];r&&(ce(r)&&(r={mounted:r,updated:r}),r.deep&&qn(o),s.push({dir:r,instance:n,value:o,oldValue:void 0,arg:l,modifiers:c}))}return t}function Rn(t,e,n,s){const i=t.dirs,r=e&&e.dirs;for(let o=0;o<i.length;o++){const l=i[o];r&&(l.oldValue=r[o].value);let c=l.dir[s];c&&($s(),En(c,n,8,[t.el,l,t,e]),Hs())}}const Sy=Symbol("_vte"),Ay=t=>t.__isTeleport,Jr=t=>t&&(t.disabled||t.disabled===""),NC=t=>t&&(t.defer||t.defer===""),Np=t=>typeof SVGElement<"u"&&t instanceof SVGElement,xp=t=>typeof MathMLElement=="function"&&t instanceof MathMLElement,Bu=(t,e)=>{const n=t&&t.to;return He(n)?e?e(n):null:n},xC={name:"Teleport",__isTeleport:!0,process(t,e,n,s,i,r,o,l,c,u){const{mc:h,pc:f,pbc:g,o:{insert:m,querySelector:I,createText:P,createComment:D}}=u,M=Jr(e.props);let{shapeFlag:x,children:b,dynamicChildren:R}=e;if(t==null){const N=e.el=P(""),F=e.anchor=P("");m(N,n,s),m(F,n,s);const T=(y,v)=>{x&16&&(i&&i.isCE&&(i.ce._teleportTarget=y),h(b,y,v,i,r,o,l,c))},w=()=>{const y=e.target=Bu(e.props,I),v=ky(y,e,P,m);y&&(o!=="svg"&&Np(y)?o="svg":o!=="mathml"&&xp(y)&&(o="mathml"),M||(T(y,v),Ua(e)))};M&&(T(n,F),Ua(e)),NC(e.props)?ot(w,r):w()}else{e.el=t.el,e.targetStart=t.targetStart;const N=e.anchor=t.anchor,F=e.target=t.target,T=e.targetAnchor=t.targetAnchor,w=Jr(t.props),y=w?n:F,v=w?N:T;if(o==="svg"||Np(F)?o="svg":(o==="mathml"||xp(F))&&(o="mathml"),R?(g(t.dynamicChildren,R,y,i,r,o,l),dd(t,e,!0)):c||f(t,e,y,v,i,r,o,l,!1),M)w?e.props&&t.props&&e.props.to!==t.props.to&&(e.props.to=t.props.to):ba(e,n,N,u,1);else if((e.props&&e.props.to)!==(t.props&&t.props.to)){const A=e.target=Bu(e.props,I);A&&ba(e,A,null,u,0)}else w&&ba(e,F,T,u,1);Ua(e)}},remove(t,e,n,{um:s,o:{remove:i}},r){const{shapeFlag:o,children:l,anchor:c,targetStart:u,targetAnchor:h,target:f,props:g}=t;if(f&&(i(u),i(h)),r&&i(c),o&16){const m=r||!Jr(g);for(let I=0;I<l.length;I++){const P=l[I];s(P,e,n,m,!!P.dynamicChildren)}}},move:ba,hydrate:DC};function ba(t,e,n,{o:{insert:s},m:i},r=2){r===0&&s(t.targetAnchor,e,n);const{el:o,anchor:l,shapeFlag:c,children:u,props:h}=t,f=r===2;if(f&&s(o,e,n),(!f||Jr(h))&&c&16)for(let g=0;g<u.length;g++)i(u[g],e,n,2);f&&s(l,e,n)}function DC(t,e,n,s,i,r,{o:{nextSibling:o,parentNode:l,querySelector:c,insert:u,createText:h}},f){const g=e.target=Bu(e.props,c);if(g){const m=g._lpa||g.firstChild;if(e.shapeFlag&16)if(Jr(e.props))e.anchor=f(o(t),e,l(t),n,s,i,r),e.targetStart=m,e.targetAnchor=m&&o(m);else{e.anchor=o(t);let I=m;for(;I;){if(I&&I.nodeType===8){if(I.data==="teleport start anchor")e.targetStart=I;else if(I.data==="teleport anchor"){e.targetAnchor=I,g._lpa=e.targetAnchor&&o(e.targetAnchor);break}}I=o(I)}e.targetAnchor||ky(g,e,h,u),f(m&&o(m),e,g,n,s,i,r)}Ua(e)}return e.anchor&&o(e.anchor)}const J4=xC;function Ua(t){const e=t.ctx;if(e&&e.ut){let n=t.targetStart;for(;n&&n!==t.targetAnchor;)n.nodeType===1&&n.setAttribute("data-v-owner",e.uid),n=n.nextSibling;e.ut()}}function ky(t,e,n,s){const i=e.targetStart=n(""),r=e.targetAnchor=n("");return i[Sy]=r,t&&(s(i,t),s(r,t)),r}const bs=Symbol("_leaveCb"),Ea=Symbol("_enterCb");function LC(){const t={isMounted:!1,isLeaving:!1,isUnmounting:!1,leavingVNodes:new Map};return Jl(()=>{t.isMounted=!0}),Ql(()=>{t.isUnmounting=!0}),t}const rn=[Function,Array],Ry={mode:String,appear:Boolean,persisted:Boolean,onBeforeEnter:rn,onEnter:rn,onAfterEnter:rn,onEnterCancelled:rn,onBeforeLeave:rn,onLeave:rn,onAfterLeave:rn,onLeaveCancelled:rn,onBeforeAppear:rn,onAppear:rn,onAfterAppear:rn,onAppearCancelled:rn},Py=t=>{const e=t.subTree;return e.component?Py(e.component):e},MC={name:"BaseTransition",props:Ry,setup(t,{slots:e}){const n=Ei(),s=LC();return()=>{const i=e.default&&xy(e.default(),!0);if(!i||!i.length)return;const r=Oy(i),o=be(t),{mode:l}=o;if(s.isLeaving)return zc(r);const c=Dp(r);if(!c)return zc(r);let u=ju(c,o,s,n,g=>u=g);c.type!==et&&ar(c,u);const h=n.subTree,f=h&&Dp(h);if(f&&f.type!==et&&!mn(c,f)&&Py(n).type!==et){const g=ju(f,o,s,n);if(ar(f,g),l==="out-in"&&c.type!==et)return s.isLeaving=!0,g.afterLeave=()=>{s.isLeaving=!1,n.job.flags&8||n.update(),delete g.afterLeave},zc(r);l==="in-out"&&c.type!==et&&(g.delayLeave=(m,I,P)=>{const D=Ny(s,f);D[String(f.key)]=f,m[bs]=()=>{I(),m[bs]=void 0,delete u.delayedLeave},u.delayedLeave=P})}return r}}};function Oy(t){let e=t[0];if(t.length>1){for(const n of t)if(n.type!==et){e=n;break}}return e}const FC=MC;function Ny(t,e){const{leavingVNodes:n}=t;let s=n.get(e.type);return s||(s=Object.create(null),n.set(e.type,s)),s}function ju(t,e,n,s,i){const{appear:r,mode:o,persisted:l=!1,onBeforeEnter:c,onEnter:u,onAfterEnter:h,onEnterCancelled:f,onBeforeLeave:g,onLeave:m,onAfterLeave:I,onLeaveCancelled:P,onBeforeAppear:D,onAppear:M,onAfterAppear:x,onAppearCancelled:b}=e,R=String(t.key),N=Ny(n,t),F=(y,v)=>{y&&En(y,s,9,v)},T=(y,v)=>{const A=v[1];F(y,v),re(y)?y.every(S=>S.length<=1)&&A():y.length<=1&&A()},w={mode:o,persisted:l,beforeEnter(y){let v=c;if(!n.isMounted)if(r)v=D||c;else return;y[bs]&&y[bs](!0);const A=N[R];A&&mn(t,A)&&A.el[bs]&&A.el[bs](),F(v,[y])},enter(y){let v=u,A=h,S=f;if(!n.isMounted)if(r)v=M||u,A=x||h,S=b||f;else return;let E=!1;const he=y[Ea]=pe=>{E||(E=!0,pe?F(S,[y]):F(A,[y]),w.delayedLeave&&w.delayedLeave(),y[Ea]=void 0)};v?T(v,[y,he]):he()},leave(y,v){const A=String(t.key);if(y[Ea]&&y[Ea](!0),n.isUnmounting)return v();F(g,[y]);let S=!1;const E=y[bs]=he=>{S||(S=!0,v(),he?F(P,[y]):F(I,[y]),y[bs]=void 0,N[A]===t&&delete N[A])};N[A]=t,m?T(m,[y,E]):E()},clone(y){const v=ju(y,e,n,s,i);return i&&i(v),v}};return w}function zc(t){if(xo(t))return t=ss(t),t.children=null,t}function Dp(t){if(!xo(t))return Ay(t.type)&&t.children?Oy(t.children):t;const{shapeFlag:e,children:n}=t;if(n){if(e&16)return n[0];if(e&32&&ce(n.default))return n.default()}}function ar(t,e){t.shapeFlag&6&&t.component?(t.transition=e,ar(t.component.subTree,e)):t.shapeFlag&128?(t.ssContent.transition=e.clone(t.ssContent),t.ssFallback.transition=e.clone(t.ssFallback)):t.transition=e}function xy(t,e=!1,n){let s=[],i=0;for(let r=0;r<t.length;r++){let o=t[r];const l=n==null?o.key:String(n)+String(o.key!=null?o.key:r);o.type===Et?(o.patchFlag&128&&i++,s=s.concat(xy(o.children,e,l))):(e||o.type!==et)&&s.push(l!=null?ss(o,{key:l}):o)}if(i>1)for(let r=0;r<s.length;r++)s[r].patchFlag=-2;return s}/*! #__NO_SIDE_EFFECTS__ */function ls(t,e){return ce(t)?Ze({name:t.name},e,{setup:t}):t}function cd(t){t.ids=[t.ids[0]+t.ids[2]+++"-",0,0]}function Qa(t,e,n,s,i=!1){if(re(t)){t.forEach((I,P)=>Qa(I,e&&(re(e)?e[P]:e),n,s,i));return}if(xs(s)&&!i)return;const r=s.shapeFlag&4?tc(s.component):s.el,o=i?null:r,{i:l,r:c}=t,u=e&&e.r,h=l.refs===Te?l.refs={}:l.refs,f=l.setupState,g=be(f),m=f===Te?()=>!1:I=>Ce(g,I);if(u!=null&&u!==c&&(He(u)?(h[u]=null,m(u)&&(f[u]=null)):ut(u)&&(u.value=null)),ce(c))No(c,l,12,[o,h]);else{const I=He(c),P=ut(c);if(I||P){const D=()=>{if(t.f){const M=I?m(c)?f[c]:h[c]:c.value;i?re(M)&&Xh(M,r):re(M)?M.includes(r)||M.push(r):I?(h[c]=[r],m(c)&&(f[c]=h[c])):(c.value=[r],t.k&&(h[t.k]=c.value))}else I?(h[c]=o,m(c)&&(f[c]=o)):P&&(c.value=o,t.k&&(h[t.k]=o))};o?(D.id=-1,ot(D,n)):D()}}}let Lp=!1;const Mi=()=>{Lp||(console.error("Hydration completed but contains mismatches."),Lp=!0)},UC=t=>t.namespaceURI.includes("svg")&&t.tagName!=="foreignObject",$C=t=>t.namespaceURI.includes("MathML"),Ta=t=>{if(t.nodeType===1){if(UC(t))return"svg";if($C(t))return"mathml"}},Bi=t=>t.nodeType===8;function HC(t){const{mt:e,p:n,o:{patchProp:s,createText:i,nextSibling:r,parentNode:o,remove:l,insert:c,createComment:u}}=t,h=(b,R)=>{if(!R.hasChildNodes()){n(null,b,R),Xa(),R._vnode=b;return}f(R.firstChild,b,null,null,null),Xa(),R._vnode=b},f=(b,R,N,F,T,w=!1)=>{w=w||!!R.dynamicChildren;const y=Bi(b)&&b.data==="[",v=()=>P(b,R,N,F,T,y),{type:A,ref:S,shapeFlag:E,patchFlag:he}=R;let pe=b.nodeType;R.el=b,he===-2&&(w=!1,R.dynamicChildren=null);let ee=null;switch(A){case ci:pe!==3?R.children===""?(c(R.el=i(""),o(b),b),ee=b):ee=v():(b.data!==R.children&&(Mi(),b.data=R.children),ee=r(b));break;case et:x(b)?(ee=r(b),M(R.el=b.content.firstChild,b,N)):pe!==8||y?ee=v():ee=r(b);break;case Zr:if(y&&(b=r(b),pe=b.nodeType),pe===1||pe===3){ee=b;const le=!R.children.length;for(let Z=0;Z<R.staticCount;Z++)le&&(R.children+=ee.nodeType===1?ee.outerHTML:ee.data),Z===R.staticCount-1&&(R.anchor=ee),ee=r(ee);return y?r(ee):ee}else v();break;case Et:y?ee=I(b,R,N,F,T,w):ee=v();break;default:if(E&1)(pe!==1||R.type.toLowerCase()!==b.tagName.toLowerCase())&&!x(b)?ee=v():ee=g(b,R,N,F,T,w);else if(E&6){R.slotScopeIds=T;const le=o(b);if(y?ee=D(b):Bi(b)&&b.data==="teleport start"?ee=D(b,b.data,"teleport end"):ee=r(b),e(R,le,null,N,F,Ta(le),w),xs(R)){let Z;y?(Z=De(Et),Z.anchor=ee?ee.previousSibling:le.lastChild):Z=b.nodeType===3?_d(""):De("div"),Z.el=b,R.component.subTree=Z}}else E&64?pe!==8?ee=v():ee=R.type.hydrate(b,R,N,F,T,w,t,m):E&128&&(ee=R.type.hydrate(b,R,N,F,Ta(o(b)),T,w,t,f))}return S!=null&&Qa(S,null,F,R),ee},g=(b,R,N,F,T,w)=>{w=w||!!R.dynamicChildren;const{type:y,props:v,patchFlag:A,shapeFlag:S,dirs:E,transition:he}=R,pe=y==="input"||y==="option";if(pe||A!==-1){E&&Rn(R,null,N,"created");let ee=!1;if(x(b)){ee=iw(F,he)&&N&&N.vnode.props&&N.vnode.props.appear;const Z=b.content.firstChild;ee&&he.beforeEnter(Z),M(Z,b,N),R.el=b=Z}if(S&16&&!(v&&(v.innerHTML||v.textContent))){let Z=m(b.firstChild,R,b,N,F,T,w);for(;Z;){Ca(b,1)||Mi();const Ve=Z;Z=Z.nextSibling,l(Ve)}}else if(S&8){let Z=R.children;Z[0]===`
`&&(b.tagName==="PRE"||b.tagName==="TEXTAREA")&&(Z=Z.slice(1)),b.textContent!==Z&&(Ca(b,0)||Mi(),b.textContent=R.children)}if(v){if(pe||!w||A&48){const Z=b.tagName.includes("-");for(const Ve in v)(pe&&(Ve.endsWith("value")||Ve==="indeterminate")||Po(Ve)&&!Gi(Ve)||Ve[0]==="."||Z)&&s(b,Ve,null,v[Ve],void 0,N)}else if(v.onClick)s(b,"onClick",null,v.onClick,void 0,N);else if(A&4&&ri(v.style))for(const Z in v.style)v.style[Z]}let le;(le=v&&v.onVnodeBeforeMount)&&Ut(le,N,R),E&&Rn(R,null,N,"beforeMount"),((le=v&&v.onVnodeMounted)||E||ee)&&uw(()=>{le&&Ut(le,N,R),ee&&he.enter(b),E&&Rn(R,null,N,"mounted")},F)}return b.nextSibling},m=(b,R,N,F,T,w,y)=>{y=y||!!R.dynamicChildren;const v=R.children,A=v.length;for(let S=0;S<A;S++){const E=y?v[S]:v[S]=Yt(v[S]),he=E.type===ci;b?(he&&!y&&S+1<A&&Yt(v[S+1]).type===ci&&(c(i(b.data.slice(E.children.length)),N,r(b)),b.data=E.children),b=f(b,E,F,T,w,y)):he&&!E.children?c(E.el=i(""),N):(Ca(N,1)||Mi(),n(null,E,N,null,F,T,Ta(N),w))}return b},I=(b,R,N,F,T,w)=>{const{slotScopeIds:y}=R;y&&(T=T?T.concat(y):y);const v=o(b),A=m(r(b),R,v,N,F,T,w);return A&&Bi(A)&&A.data==="]"?r(R.anchor=A):(Mi(),c(R.anchor=u("]"),v,A),A)},P=(b,R,N,F,T,w)=>{if(Ca(b.parentElement,1)||Mi(),R.el=null,w){const A=D(b);for(;;){const S=r(b);if(S&&S!==A)l(S);else break}}const y=r(b),v=o(b);return l(b),n(null,R,v,y,N,F,Ta(v),T),y},D=(b,R="[",N="]")=>{let F=0;for(;b;)if(b=r(b),b&&Bi(b)&&(b.data===R&&F++,b.data===N)){if(F===0)return r(b);F--}return b},M=(b,R,N)=>{const F=R.parentNode;F&&F.replaceChild(b,R);let T=N;for(;T;)T.vnode.el===R&&(T.vnode.el=T.subTree.el=b),T=T.parent},x=b=>b.nodeType===1&&b.tagName==="TEMPLATE";return[h,f]}const Mp="data-allow-mismatch",BC={0:"text",1:"children",2:"class",3:"style",4:"attribute"};function Ca(t,e){if(e===0||e===1)for(;t&&!t.hasAttribute(Mp);)t=t.parentElement;const n=t&&t.getAttribute(Mp);if(n==null)return!1;if(n==="")return!0;{const s=n.split(",");return e===0&&s.includes("children")?!0:n.split(",").includes(BC[e])}}function jC(t,e){if(Bi(t)&&t.data==="["){let n=1,s=t.nextSibling;for(;s;){if(s.nodeType===1){if(e(s)===!1)break}else if(Bi(s))if(s.data==="]"){if(--n===0)break}else s.data==="["&&n++;s=s.nextSibling}}else e(t)}const xs=t=>!!t.type.__asyncLoader;/*! #__NO_SIDE_EFFECTS__ */function Fp(t){ce(t)&&(t={loader:t});const{loader:e,loadingComponent:n,errorComponent:s,delay:i=200,hydrate:r,timeout:o,suspensible:l=!0,onError:c}=t;let u=null,h,f=0;const g=()=>(f++,u=null,m()),m=()=>{let I;return u||(I=u=e().catch(P=>{if(P=P instanceof Error?P:new Error(String(P)),c)return new Promise((D,M)=>{c(P,()=>D(g()),()=>M(P),f+1)});throw P}).then(P=>I!==u&&u?u:(P&&(P.__esModule||P[Symbol.toStringTag]==="Module")&&(P=P.default),h=P,P)))};return ls({name:"AsyncComponentWrapper",__asyncLoader:m,__asyncHydrate(I,P,D){const M=r?()=>{const x=r(D,b=>jC(I,b));x&&(P.bum||(P.bum=[])).push(x)}:D;h?M():m().then(()=>!P.isUnmounted&&M())},get __asyncResolved(){return h},setup(){const I=tt;if(cd(I),h)return()=>Gc(h,I);const P=b=>{u=null,yr(b,I,13,!s)};if(l&&I.suspense||Lo)return m().then(b=>()=>Gc(b,I)).catch(b=>(P(b),()=>s?De(s,{error:b}):null));const D=Ge(!1),M=Ge(),x=Ge(!!i);return i&&setTimeout(()=>{x.value=!1},i),o!=null&&setTimeout(()=>{if(!D.value&&!M.value){const b=new Error(`Async component timed out after ${o}ms.`);P(b),M.value=b}},o),m().then(()=>{D.value=!0,I.parent&&xo(I.parent.vnode)&&I.parent.update()}).catch(b=>{P(b),M.value=b}),()=>{if(D.value&&h)return Gc(h,I);if(M.value&&s)return De(s,{error:M.value});if(n&&!x.value)return De(n)}}})}function Gc(t,e){const{ref:n,props:s,children:i,ce:r}=e.vnode,o=De(t,s,i);return o.ref=n,o.ce=r,delete e.vnode.ce,o}const xo=t=>t.type.__isKeepAlive,VC={name:"KeepAlive",__isKeepAlive:!0,props:{include:[String,RegExp,Array],exclude:[String,RegExp,Array],max:[String,Number]},setup(t,{slots:e}){const n=Ei(),s=n.ctx;if(!s.renderer)return()=>{const x=e.default&&e.default();return x&&x.length===1?x[0]:x};const i=new Map,r=new Set;let o=null;const l=n.suspense,{renderer:{p:c,m:u,um:h,o:{createElement:f}}}=s,g=f("div");s.activate=(x,b,R,N,F)=>{const T=x.component;u(x,b,R,0,l),c(T.vnode,x,b,R,T,l,N,x.slotScopeIds,F),ot(()=>{T.isDeactivated=!1,T.a&&Yi(T.a);const w=x.props&&x.props.onVnodeMounted;w&&Ut(w,T.parent,x)},l)},s.deactivate=x=>{const b=x.component;tl(b.m),tl(b.a),u(x,g,null,1,l),ot(()=>{b.da&&Yi(b.da);const R=x.props&&x.props.onVnodeUnmounted;R&&Ut(R,b.parent,x),b.isDeactivated=!0},l)};function m(x){Yc(x),h(x,n,l,!0)}function I(x){i.forEach((b,R)=>{const N=Xu(b.type);N&&!x(N)&&P(R)})}function P(x){const b=i.get(x);b&&(!o||!mn(b,o))?m(b):o&&Yc(o),i.delete(x),r.delete(x)}li(()=>[t.include,t.exclude],([x,b])=>{x&&I(R=>zr(x,R)),b&&I(R=>!zr(b,R))},{flush:"post",deep:!0});let D=null;const M=()=>{D!=null&&(nl(n.subTree.type)?ot(()=>{i.set(D,Ia(n.subTree))},n.subTree.suspense):i.set(D,Ia(n.subTree)))};return Jl(M),Fy(M),Ql(()=>{i.forEach(x=>{const{subTree:b,suspense:R}=n,N=Ia(b);if(x.type===N.type&&x.key===N.key){Yc(N);const F=N.component.da;F&&ot(F,R);return}m(x)})}),()=>{if(D=null,!e.default)return o=null;const x=e.default(),b=x[0];if(x.length>1)return o=null,x;if(!cr(b)||!(b.shapeFlag&4)&&!(b.shapeFlag&128))return o=null,b;let R=Ia(b);if(R.type===et)return o=null,R;const N=R.type,F=Xu(xs(R)?R.type.__asyncResolved||{}:N),{include:T,exclude:w,max:y}=t;if(T&&(!F||!zr(T,F))||w&&F&&zr(w,F))return R.shapeFlag&=-257,o=R,b;const v=R.key==null?N:R.key,A=i.get(v);return R.el&&(R=ss(R),b.shapeFlag&128&&(b.ssContent=R)),D=v,A?(R.el=A.el,R.component=A.component,R.transition&&ar(R,R.transition),R.shapeFlag|=512,r.delete(v),r.add(v)):(r.add(v),y&&r.size>parseInt(y,10)&&P(r.values().next().value)),R.shapeFlag|=256,o=R,nl(b.type)?b:R}}},WC=VC;function zr(t,e){return re(t)?t.some(n=>zr(n,e)):He(t)?t.split(",").includes(e):UT(t)?(t.lastIndex=0,t.test(e)):!1}function Dy(t,e){My(t,"a",e)}function Ly(t,e){My(t,"da",e)}function My(t,e,n=tt){const s=t.__wdc||(t.__wdc=()=>{let i=n;for(;i;){if(i.isDeactivated)return;i=i.parent}return t()});if(Xl(e,s,n),n){let i=n.parent;for(;i&&i.parent;)xo(i.parent.vnode)&&KC(s,e,n,i),i=i.parent}}function KC(t,e,n,s){const i=Xl(e,t,s,!0);Uy(()=>{Xh(s[e],i)},n)}function Yc(t){t.shapeFlag&=-257,t.shapeFlag&=-513}function Ia(t){return t.shapeFlag&128?t.ssContent:t}function Xl(t,e,n=tt,s=!1){if(n){const i=n[t]||(n[t]=[]),r=e.__weh||(e.__weh=(...o)=>{$s();const l=Do(n),c=En(e,n,t,o);return l(),Hs(),c});return s?i.unshift(r):i.push(r),r}}const cs=t=>(e,n=tt)=>{(!Lo||t==="sp")&&Xl(t,(...s)=>e(...s),n)},qC=cs("bm"),Jl=cs("m"),zC=cs("bu"),Fy=cs("u"),Ql=cs("bum"),Uy=cs("um"),GC=cs("sp"),YC=cs("rtg"),XC=cs("rtc");function $y(t,e=tt){Xl("ec",t,e)}const Hy="components";function Q4(t,e){return jy(Hy,t,!0,e)||t}const By=Symbol.for("v-ndc");function JC(t){return He(t)?jy(Hy,t,!1)||t:t||By}function jy(t,e,n=!0,s=!1){const i=it||tt;if(i){const r=i.type;{const l=Xu(r,!1);if(l&&(l===e||l===un(e)||l===Wl(un(e))))return r}const o=Up(i[t]||r[t],e)||Up(i.appContext[t],e);return!o&&s?r:o}}function Up(t,e){return t&&(t[e]||t[un(e)]||t[Wl(un(e))])}function Z4(t,e,n,s){let i;const r=n,o=re(t);if(o||He(t)){const l=o&&ri(t);let c=!1;l&&(c=!cn(t),t=Gl(t)),i=new Array(t.length);for(let u=0,h=t.length;u<h;u++)i[u]=e(c?bt(t[u]):t[u],u,void 0,r)}else if(typeof t=="number"){i=new Array(t);for(let l=0;l<t;l++)i[l]=e(l+1,l,void 0,r)}else if(Le(t))if(t[Symbol.iterator])i=Array.from(t,(l,c)=>e(l,c,void 0,r));else{const l=Object.keys(t);i=new Array(l.length);for(let c=0,u=l.length;c<u;c++){const h=l[c];i[c]=e(t[h],h,c,r)}}else i=[];return i}function eH(t,e){for(let n=0;n<e.length;n++){const s=e[n];if(re(s))for(let i=0;i<s.length;i++)t[s[i].name]=s[i].fn;else s&&(t[s.name]=s.key?(...i)=>{const r=s.fn(...i);return r&&(r.key=s.key),r}:s.fn)}return t}function tH(t,e,n={},s,i){if(it.ce||it.parent&&xs(it.parent)&&it.parent.ce)return e!=="default"&&(n.name=e),Gt(),zn(Et,null,[De("slot",n,s)],64);let r=t[e];r&&r._c&&(r._d=!1),Gt();const o=r&&Vy(r(n)),l=zn(Et,{key:(n.key||o&&o.key||`_${e}`)+(!o&&s?"_fb":"")},o||[],o&&t._===1?64:-2);return l.scopeId&&(l.slotScopeIds=[l.scopeId+"-s"]),r&&r._c&&(r._d=!0),l}function Vy(t){return t.some(e=>cr(e)?!(e.type===et||e.type===Et&&!Vy(e.children)):!0)?t:null}const Vu=t=>t?mw(t)?tc(t):Vu(t.parent):null,Qr=Ze(Object.create(null),{$:t=>t,$el:t=>t.vnode.el,$data:t=>t.data,$props:t=>t.props,$attrs:t=>t.attrs,$slots:t=>t.slots,$refs:t=>t.refs,$parent:t=>Vu(t.parent),$root:t=>Vu(t.root),$host:t=>t.ce,$emit:t=>t.emit,$options:t=>ud(t),$forceUpdate:t=>t.f||(t.f=()=>{ad(t.update)}),$nextTick:t=>t.n||(t.n=bi.bind(t.proxy)),$watch:t=>wI.bind(t)}),Xc=(t,e)=>t!==Te&&!t.__isScriptSetup&&Ce(t,e),QC={get({_:t},e){if(e==="__v_skip")return!0;const{ctx:n,setupState:s,data:i,props:r,accessCache:o,type:l,appContext:c}=t;let u;if(e[0]!=="$"){const m=o[e];if(m!==void 0)switch(m){case 1:return s[e];case 2:return i[e];case 4:return n[e];case 3:return r[e]}else{if(Xc(s,e))return o[e]=1,s[e];if(i!==Te&&Ce(i,e))return o[e]=2,i[e];if((u=t.propsOptions[0])&&Ce(u,e))return o[e]=3,r[e];if(n!==Te&&Ce(n,e))return o[e]=4,n[e];Wu&&(o[e]=0)}}const h=Qr[e];let f,g;if(h)return e==="$attrs"&&At(t.attrs,"get",""),h(t);if((f=l.__cssModules)&&(f=f[e]))return f;if(n!==Te&&Ce(n,e))return o[e]=4,n[e];if(g=c.config.globalProperties,Ce(g,e))return g[e]},set({_:t},e,n){const{data:s,setupState:i,ctx:r}=t;return Xc(i,e)?(i[e]=n,!0):s!==Te&&Ce(s,e)?(s[e]=n,!0):Ce(t.props,e)||e[0]==="$"&&e.slice(1)in t?!1:(r[e]=n,!0)},has({_:{data:t,setupState:e,accessCache:n,ctx:s,appContext:i,propsOptions:r}},o){let l;return!!n[o]||t!==Te&&Ce(t,o)||Xc(e,o)||(l=r[0])&&Ce(l,o)||Ce(s,o)||Ce(Qr,o)||Ce(i.config.globalProperties,o)},defineProperty(t,e,n){return n.get!=null?t._.accessCache[e]=0:Ce(n,"value")&&this.set(t,e,n.value,null),Reflect.defineProperty(t,e,n)}};function nH(){return Wy().slots}function sH(){return Wy().attrs}function Wy(){const t=Ei();return t.setupContext||(t.setupContext=yw(t))}function Za(t){return re(t)?t.reduce((e,n)=>(e[n]=null,e),{}):t}function iH(t,e){return!t||!e?t||e:re(t)&&re(e)?t.concat(e):Ze({},Za(t),Za(e))}let Wu=!0;function ZC(t){const e=ud(t),n=t.proxy,s=t.ctx;Wu=!1,e.beforeCreate&&$p(e.beforeCreate,t,"bc");const{data:i,computed:r,methods:o,watch:l,provide:c,inject:u,created:h,beforeMount:f,mounted:g,beforeUpdate:m,updated:I,activated:P,deactivated:D,beforeDestroy:M,beforeUnmount:x,destroyed:b,unmounted:R,render:N,renderTracked:F,renderTriggered:T,errorCaptured:w,serverPrefetch:y,expose:v,inheritAttrs:A,components:S,directives:E,filters:he}=e;if(u&&eI(u,s,null),o)for(const le in o){const Z=o[le];ce(Z)&&(s[le]=Z.bind(n))}if(i){const le=i.call(n,n);Le(le)&&(t.data=In(le))}if(Wu=!0,r)for(const le in r){const Z=r[le],Ve=ce(Z)?Z.bind(n,n):ce(Z.get)?Z.get.bind(n,n):Nn,fn=!ce(Z)&&ce(Z.set)?Z.set.bind(n):Nn,tn=an({get:Ve,set:fn});Object.defineProperty(s,le,{enumerable:!0,configurable:!0,get:()=>tn.value,set:We=>tn.value=We})}if(l)for(const le in l)Ky(l[le],s,n,le);if(c){const le=ce(c)?c.call(n):c;Reflect.ownKeys(le).forEach(Z=>{ai(Z,le[Z])})}h&&$p(h,t,"c");function ee(le,Z){re(Z)?Z.forEach(Ve=>le(Ve.bind(n))):Z&&le(Z.bind(n))}if(ee(qC,f),ee(Jl,g),ee(zC,m),ee(Fy,I),ee(Dy,P),ee(Ly,D),ee($y,w),ee(XC,F),ee(YC,T),ee(Ql,x),ee(Uy,R),ee(GC,y),re(v))if(v.length){const le=t.exposed||(t.exposed={});v.forEach(Z=>{Object.defineProperty(le,Z,{get:()=>n[Z],set:Ve=>n[Z]=Ve})})}else t.exposed||(t.exposed={});N&&t.render===Nn&&(t.render=N),A!=null&&(t.inheritAttrs=A),S&&(t.components=S),E&&(t.directives=E),y&&cd(t)}function eI(t,e,n=Nn){re(t)&&(t=Ku(t));for(const s in t){const i=t[s];let r;Le(i)?"default"in i?r=ht(i.from||s,i.default,!0):r=ht(i.from||s):r=ht(i),ut(r)?Object.defineProperty(e,s,{enumerable:!0,configurable:!0,get:()=>r.value,set:o=>r.value=o}):e[s]=r}}function $p(t,e,n){En(re(t)?t.map(s=>s.bind(e.proxy)):t.bind(e.proxy),e,n)}function Ky(t,e,n,s){let i=s.includes(".")?ow(n,s):()=>n[s];if(He(t)){const r=e[t];ce(r)&&li(i,r)}else if(ce(t))li(i,t.bind(n));else if(Le(t))if(re(t))t.forEach(r=>Ky(r,e,n,s));else{const r=ce(t.handler)?t.handler.bind(n):e[t.handler];ce(r)&&li(i,r,t)}}function ud(t){const e=t.type,{mixins:n,extends:s}=e,{mixins:i,optionsCache:r,config:{optionMergeStrategies:o}}=t.appContext,l=r.get(e);let c;return l?c=l:!i.length&&!n&&!s?c=e:(c={},i.length&&i.forEach(u=>el(c,u,o,!0)),el(c,e,o)),Le(e)&&r.set(e,c),c}function el(t,e,n,s=!1){const{mixins:i,extends:r}=e;r&&el(t,r,n,!0),i&&i.forEach(o=>el(t,o,n,!0));for(const o in e)if(!(s&&o==="expose")){const l=tI[o]||n&&n[o];t[o]=l?l(t[o],e[o]):e[o]}return t}const tI={data:Hp,props:Bp,emits:Bp,methods:Gr,computed:Gr,beforeCreate:Nt,created:Nt,beforeMount:Nt,mounted:Nt,beforeUpdate:Nt,updated:Nt,beforeDestroy:Nt,beforeUnmount:Nt,destroyed:Nt,unmounted:Nt,activated:Nt,deactivated:Nt,errorCaptured:Nt,serverPrefetch:Nt,components:Gr,directives:Gr,watch:sI,provide:Hp,inject:nI};function Hp(t,e){return e?t?function(){return Ze(ce(t)?t.call(this,this):t,ce(e)?e.call(this,this):e)}:e:t}function nI(t,e){return Gr(Ku(t),Ku(e))}function Ku(t){if(re(t)){const e={};for(let n=0;n<t.length;n++)e[t[n]]=t[n];return e}return t}function Nt(t,e){return t?[...new Set([].concat(t,e))]:e}function Gr(t,e){return t?Ze(Object.create(null),t,e):e}function Bp(t,e){return t?re(t)&&re(e)?[...new Set([...t,...e])]:Ze(Object.create(null),Za(t),Za(e??{})):e}function sI(t,e){if(!t)return e;if(!e)return t;const n=Ze(Object.create(null),t);for(const s in e)n[s]=Nt(t[s],e[s]);return n}function qy(){return{app:null,config:{isNativeTag:MT,performance:!1,globalProperties:{},optionMergeStrategies:{},errorHandler:void 0,warnHandler:void 0,compilerOptions:{}},mixins:[],components:{},directives:{},provides:Object.create(null),optionsCache:new WeakMap,propsCache:new WeakMap,emitsCache:new WeakMap}}let iI=0;function rI(t,e){return function(s,i=null){ce(s)||(s=Ze({},s)),i!=null&&!Le(i)&&(i=null);const r=qy(),o=new WeakSet,l=[];let c=!1;const u=r.app={_uid:iI++,_component:s,_props:i,_container:null,_context:r,_instance:null,version:ww,get config(){return r.config},set config(h){},use(h,...f){return o.has(h)||(h&&ce(h.install)?(o.add(h),h.install(u,...f)):ce(h)&&(o.add(h),h(u,...f))),u},mixin(h){return r.mixins.includes(h)||r.mixins.push(h),u},component(h,f){return f?(r.components[h]=f,u):r.components[h]},directive(h,f){return f?(r.directives[h]=f,u):r.directives[h]},mount(h,f,g){if(!c){const m=u._ceVNode||De(s,i);return m.appContext=r,g===!0?g="svg":g===!1&&(g=void 0),f&&e?e(m,h):t(m,h,g),c=!0,u._container=h,h.__vue_app__=u,tc(m.component)}},onUnmount(h){l.push(h)},unmount(){c&&(En(l,u._instance,16),t(null,u._container),delete u._container.__vue_app__)},provide(h,f){return r.provides[h]=f,u},runWithContext(h){const f=oi;oi=u;try{return h()}finally{oi=f}}};return u}}let oi=null;function ai(t,e){if(tt){let n=tt.provides;const s=tt.parent&&tt.parent.provides;s===n&&(n=tt.provides=Object.create(s)),n[t]=e}}function ht(t,e,n=!1){const s=tt||it;if(s||oi){const i=oi?oi._context.provides:s?s.parent==null?s.vnode.appContext&&s.vnode.appContext.provides:s.parent.provides:void 0;if(i&&t in i)return i[t];if(arguments.length>1)return n&&ce(e)?e.call(s&&s.proxy):e}}function zy(){return!!(tt||it||oi)}const Gy={},Yy=()=>Object.create(Gy),Xy=t=>Object.getPrototypeOf(t)===Gy;function oI(t,e,n,s=!1){const i={},r=Yy();t.propsDefaults=Object.create(null),Jy(t,e,i,r);for(const o in t.propsOptions[0])o in i||(i[o]=void 0);n?t.props=s?i:Gn(i):t.type.props?t.props=i:t.props=r,t.attrs=r}function aI(t,e,n,s){const{props:i,attrs:r,vnode:{patchFlag:o}}=t,l=be(i),[c]=t.propsOptions;let u=!1;if((s||o>0)&&!(o&16)){if(o&8){const h=t.vnode.dynamicProps;for(let f=0;f<h.length;f++){let g=h[f];if(ec(t.emitsOptions,g))continue;const m=e[g];if(c)if(Ce(r,g))m!==r[g]&&(r[g]=m,u=!0);else{const I=un(g);i[I]=qu(c,l,I,m,t,!1)}else m!==r[g]&&(r[g]=m,u=!0)}}}else{Jy(t,e,i,r)&&(u=!0);let h;for(const f in l)(!e||!Ce(e,f)&&((h=as(f))===f||!Ce(e,h)))&&(c?n&&(n[f]!==void 0||n[h]!==void 0)&&(i[f]=qu(c,l,f,void 0,t,!0)):delete i[f]);if(r!==l)for(const f in r)(!e||!Ce(e,f))&&(delete r[f],u=!0)}u&&Zn(t.attrs,"set","")}function Jy(t,e,n,s){const[i,r]=t.propsOptions;let o=!1,l;if(e)for(let c in e){if(Gi(c))continue;const u=e[c];let h;i&&Ce(i,h=un(c))?!r||!r.includes(h)?n[h]=u:(l||(l={}))[h]=u:ec(t.emitsOptions,c)||(!(c in s)||u!==s[c])&&(s[c]=u,o=!0)}if(r){const c=be(n),u=l||Te;for(let h=0;h<r.length;h++){const f=r[h];n[f]=qu(i,c,f,u[f],t,!Ce(u,f))}}return o}function qu(t,e,n,s,i,r){const o=t[n];if(o!=null){const l=Ce(o,"default");if(l&&s===void 0){const c=o.default;if(o.type!==Function&&!o.skipFactory&&ce(c)){const{propsDefaults:u}=i;if(n in u)s=u[n];else{const h=Do(i);s=u[n]=c.call(null,e),h()}}else s=c;i.ce&&i.ce._setProp(n,s)}o[0]&&(r&&!l?s=!1:o[1]&&(s===""||s===as(n))&&(s=!0))}return s}const lI=new WeakMap;function Qy(t,e,n=!1){const s=n?lI:e.propsCache,i=s.get(t);if(i)return i;const r=t.props,o={},l=[];let c=!1;if(!ce(t)){const h=f=>{c=!0;const[g,m]=Qy(f,e,!0);Ze(o,g),m&&l.push(...m)};!n&&e.mixins.length&&e.mixins.forEach(h),t.extends&&h(t.extends),t.mixins&&t.mixins.forEach(h)}if(!r&&!c)return Le(t)&&s.set(t,qi),qi;if(re(r))for(let h=0;h<r.length;h++){const f=un(r[h]);jp(f)&&(o[f]=Te)}else if(r)for(const h in r){const f=un(h);if(jp(f)){const g=r[h],m=o[f]=re(g)||ce(g)?{type:g}:Ze({},g),I=m.type;let P=!1,D=!0;if(re(I))for(let M=0;M<I.length;++M){const x=I[M],b=ce(x)&&x.name;if(b==="Boolean"){P=!0;break}else b==="String"&&(D=!1)}else P=ce(I)&&I.name==="Boolean";m[0]=P,m[1]=D,(P||Ce(m,"default"))&&l.push(f)}}const u=[o,l];return Le(t)&&s.set(t,u),u}function jp(t){return t[0]!=="$"&&!Gi(t)}const Zy=t=>t[0]==="_"||t==="$stable",hd=t=>re(t)?t.map(Yt):[Yt(t)],cI=(t,e,n)=>{if(e._n)return e;const s=ld((...i)=>hd(e(...i)),n);return s._c=!1,s},ew=(t,e,n)=>{const s=t._ctx;for(const i in t){if(Zy(i))continue;const r=t[i];if(ce(r))e[i]=cI(i,r,s);else if(r!=null){const o=hd(r);e[i]=()=>o}}},tw=(t,e)=>{const n=hd(e);t.slots.default=()=>n},nw=(t,e,n)=>{for(const s in e)(n||s!=="_")&&(t[s]=e[s])},uI=(t,e,n)=>{const s=t.slots=Yy();if(t.vnode.shapeFlag&32){const i=e._;i?(nw(s,e,n),n&&X_(s,"_",i,!0)):ew(e,s)}else e&&tw(t,e)},hI=(t,e,n)=>{const{vnode:s,slots:i}=t;let r=!0,o=Te;if(s.shapeFlag&32){const l=e._;l?n&&l===1?r=!1:nw(i,e,n):(r=!e.$stable,ew(e,i)),o=e}else e&&(tw(t,e),o={default:1});if(r)for(const l in i)!Zy(l)&&o[l]==null&&delete i[l]},ot=uw;function dI(t){return sw(t)}function fI(t){return sw(t,HC)}function sw(t,e){const n=Q_();n.__VUE__=!0;const{insert:s,remove:i,patchProp:r,createElement:o,createText:l,createComment:c,setText:u,setElementText:h,parentNode:f,nextSibling:g,setScopeId:m=Nn,insertStaticContent:I}=t,P=(C,k,L,j=null,H=null,V=null,G=void 0,q=null,K=!!k.dynamicChildren)=>{if(C===k)return;C&&!mn(C,k)&&(j=B(C),We(C,H,V,!0),C=null),k.patchFlag===-2&&(K=!1,k.dynamicChildren=null);const{type:W,ref:ie,shapeFlag:J}=k;switch(W){case ci:D(C,k,L,j);break;case et:M(C,k,L,j);break;case Zr:C==null&&x(k,L,j,G);break;case Et:S(C,k,L,j,H,V,G,q,K);break;default:J&1?N(C,k,L,j,H,V,G,q,K):J&6?E(C,k,L,j,H,V,G,q,K):(J&64||J&128)&&W.process(C,k,L,j,H,V,G,q,K,te)}ie!=null&&H&&Qa(ie,C&&C.ref,V,k||C,!k)},D=(C,k,L,j)=>{if(C==null)s(k.el=l(k.children),L,j);else{const H=k.el=C.el;k.children!==C.children&&u(H,k.children)}},M=(C,k,L,j)=>{C==null?s(k.el=c(k.children||""),L,j):k.el=C.el},x=(C,k,L,j)=>{[C.el,C.anchor]=I(C.children,k,L,j,C.el,C.anchor)},b=({el:C,anchor:k},L,j)=>{let H;for(;C&&C!==k;)H=g(C),s(C,L,j),C=H;s(k,L,j)},R=({el:C,anchor:k})=>{let L;for(;C&&C!==k;)L=g(C),i(C),C=L;i(k)},N=(C,k,L,j,H,V,G,q,K)=>{k.type==="svg"?G="svg":k.type==="math"&&(G="mathml"),C==null?F(k,L,j,H,V,G,q,K):y(C,k,H,V,G,q,K)},F=(C,k,L,j,H,V,G,q)=>{let K,W;const{props:ie,shapeFlag:J,transition:se,dirs:ne}=C;if(K=C.el=o(C.type,V,ie&&ie.is,ie),J&8?h(K,C.children):J&16&&w(C.children,K,null,j,H,Jc(C,V),G,q),ne&&Rn(C,null,j,"created"),T(K,C,C.scopeId,G,j),ie){for(const Ee in ie)Ee!=="value"&&!Gi(Ee)&&r(K,Ee,null,ie[Ee],V,j);"value"in ie&&r(K,"value",null,ie.value,V),(W=ie.onVnodeBeforeMount)&&Ut(W,j,C)}ne&&Rn(C,null,j,"beforeMount");const ae=iw(H,se);ae&&se.beforeEnter(K),s(K,k,L),((W=ie&&ie.onVnodeMounted)||ae||ne)&&ot(()=>{W&&Ut(W,j,C),ae&&se.enter(K),ne&&Rn(C,null,j,"mounted")},H)},T=(C,k,L,j,H)=>{if(L&&m(C,L),j)for(let V=0;V<j.length;V++)m(C,j[V]);if(H){let V=H.subTree;if(k===V||nl(V.type)&&(V.ssContent===k||V.ssFallback===k)){const G=H.vnode;T(C,G,G.scopeId,G.slotScopeIds,H.parent)}}},w=(C,k,L,j,H,V,G,q,K=0)=>{for(let W=K;W<C.length;W++){const ie=C[W]=q?Es(C[W]):Yt(C[W]);P(null,ie,k,L,j,H,V,G,q)}},y=(C,k,L,j,H,V,G)=>{const q=k.el=C.el;let{patchFlag:K,dynamicChildren:W,dirs:ie}=k;K|=C.patchFlag&16;const J=C.props||Te,se=k.props||Te;let ne;if(L&&zs(L,!1),(ne=se.onVnodeBeforeUpdate)&&Ut(ne,L,k,C),ie&&Rn(k,C,L,"beforeUpdate"),L&&zs(L,!0),(J.innerHTML&&se.innerHTML==null||J.textContent&&se.textContent==null)&&h(q,""),W?v(C.dynamicChildren,W,q,L,j,Jc(k,H),V):G||Z(C,k,q,null,L,j,Jc(k,H),V,!1),K>0){if(K&16)A(q,J,se,L,H);else if(K&2&&J.class!==se.class&&r(q,"class",null,se.class,H),K&4&&r(q,"style",J.style,se.style,H),K&8){const ae=k.dynamicProps;for(let Ee=0;Ee<ae.length;Ee++){const ye=ae[Ee],_t=J[ye],rt=se[ye];(rt!==_t||ye==="value")&&r(q,ye,_t,rt,H,L)}}K&1&&C.children!==k.children&&h(q,k.children)}else!G&&W==null&&A(q,J,se,L,H);((ne=se.onVnodeUpdated)||ie)&&ot(()=>{ne&&Ut(ne,L,k,C),ie&&Rn(k,C,L,"updated")},j)},v=(C,k,L,j,H,V,G)=>{for(let q=0;q<k.length;q++){const K=C[q],W=k[q],ie=K.el&&(K.type===Et||!mn(K,W)||K.shapeFlag&70)?f(K.el):L;P(K,W,ie,null,j,H,V,G,!0)}},A=(C,k,L,j,H)=>{if(k!==L){if(k!==Te)for(const V in k)!Gi(V)&&!(V in L)&&r(C,V,k[V],null,H,j);for(const V in L){if(Gi(V))continue;const G=L[V],q=k[V];G!==q&&V!=="value"&&r(C,V,q,G,H,j)}"value"in L&&r(C,"value",k.value,L.value,H)}},S=(C,k,L,j,H,V,G,q,K)=>{const W=k.el=C?C.el:l(""),ie=k.anchor=C?C.anchor:l("");let{patchFlag:J,dynamicChildren:se,slotScopeIds:ne}=k;ne&&(q=q?q.concat(ne):ne),C==null?(s(W,L,j),s(ie,L,j),w(k.children||[],L,ie,H,V,G,q,K)):J>0&&J&64&&se&&C.dynamicChildren?(v(C.dynamicChildren,se,L,H,V,G,q),(k.key!=null||H&&k===H.subTree)&&dd(C,k,!0)):Z(C,k,L,ie,H,V,G,q,K)},E=(C,k,L,j,H,V,G,q,K)=>{k.slotScopeIds=q,C==null?k.shapeFlag&512?H.ctx.activate(k,L,j,G,K):he(k,L,j,H,V,G,K):pe(C,k,K)},he=(C,k,L,j,H,V,G)=>{const q=C.component=LI(C,j,H);if(xo(C)&&(q.ctx.renderer=te),MI(q,!1,G),q.asyncDep){if(H&&H.registerDep(q,ee,G),!C.el){const K=q.subTree=De(et);M(null,K,k,L)}}else ee(q,C,k,L,H,V,G)},pe=(C,k,L)=>{const j=k.component=C.component;if(CI(C,k,L))if(j.asyncDep&&!j.asyncResolved){le(j,k,L);return}else j.next=k,j.update();else k.el=C.el,j.vnode=k},ee=(C,k,L,j,H,V,G)=>{const q=()=>{if(C.isMounted){let{next:J,bu:se,u:ne,parent:ae,vnode:Ee}=C;{const yt=rw(C);if(yt){J&&(J.el=Ee.el,le(C,J,G)),yt.asyncDep.then(()=>{C.isUnmounted||q()});return}}let ye=J,_t;zs(C,!1),J?(J.el=Ee.el,le(C,J,G)):J=Ee,se&&Yi(se),(_t=J.props&&J.props.onVnodeBeforeUpdate)&&Ut(_t,ae,J,Ee),zs(C,!0);const rt=Qc(C),ft=C.subTree;C.subTree=rt,P(ft,rt,f(ft.el),B(ft),C,H,V),J.el=rt.el,ye===null&&fd(C,rt.el),ne&&ot(ne,H),(_t=J.props&&J.props.onVnodeUpdated)&&ot(()=>Ut(_t,ae,J,Ee),H)}else{let J;const{el:se,props:ne}=k,{bm:ae,m:Ee,parent:ye,root:_t,type:rt}=C,ft=xs(k);if(zs(C,!1),ae&&Yi(ae),!ft&&(J=ne&&ne.onVnodeBeforeMount)&&Ut(J,ye,k),zs(C,!0),se&&Re){const yt=()=>{C.subTree=Qc(C),Re(se,C.subTree,C,H,null)};ft&&rt.__asyncHydrate?rt.__asyncHydrate(se,C,yt):yt()}else{_t.ce&&_t.ce._injectChildStyle(rt);const yt=C.subTree=Qc(C);P(null,yt,L,j,C,H,V),k.el=yt.el}if(Ee&&ot(Ee,H),!ft&&(J=ne&&ne.onVnodeMounted)){const yt=k;ot(()=>Ut(J,ye,yt),H)}(k.shapeFlag&256||ye&&xs(ye.vnode)&&ye.vnode.shapeFlag&256)&&C.a&&ot(C.a,H),C.isMounted=!0,k=L=j=null}};C.scope.on();const K=C.effect=new ry(q);C.scope.off();const W=C.update=K.run.bind(K),ie=C.job=K.runIfDirty.bind(K);ie.i=C,ie.id=C.uid,K.scheduler=()=>ad(ie),zs(C,!0),W()},le=(C,k,L)=>{k.component=C;const j=C.vnode.props;C.vnode=k,C.next=null,aI(C,k.props,j,L),hI(C,k.children,L),$s(),Op(C),Hs()},Z=(C,k,L,j,H,V,G,q,K=!1)=>{const W=C&&C.children,ie=C?C.shapeFlag:0,J=k.children,{patchFlag:se,shapeFlag:ne}=k;if(se>0){if(se&128){fn(W,J,L,j,H,V,G,q,K);return}else if(se&256){Ve(W,J,L,j,H,V,G,q,K);return}}ne&8?(ie&16&&Ft(W,H,V),J!==W&&h(L,J)):ie&16?ne&16?fn(W,J,L,j,H,V,G,q,K):Ft(W,H,V,!0):(ie&8&&h(L,""),ne&16&&w(J,L,j,H,V,G,q,K))},Ve=(C,k,L,j,H,V,G,q,K)=>{C=C||qi,k=k||qi;const W=C.length,ie=k.length,J=Math.min(W,ie);let se;for(se=0;se<J;se++){const ne=k[se]=K?Es(k[se]):Yt(k[se]);P(C[se],ne,L,null,H,V,G,q,K)}W>ie?Ft(C,H,V,!0,!1,J):w(k,L,j,H,V,G,q,K,J)},fn=(C,k,L,j,H,V,G,q,K)=>{let W=0;const ie=k.length;let J=C.length-1,se=ie-1;for(;W<=J&&W<=se;){const ne=C[W],ae=k[W]=K?Es(k[W]):Yt(k[W]);if(mn(ne,ae))P(ne,ae,L,null,H,V,G,q,K);else break;W++}for(;W<=J&&W<=se;){const ne=C[J],ae=k[se]=K?Es(k[se]):Yt(k[se]);if(mn(ne,ae))P(ne,ae,L,null,H,V,G,q,K);else break;J--,se--}if(W>J){if(W<=se){const ne=se+1,ae=ne<ie?k[ne].el:j;for(;W<=se;)P(null,k[W]=K?Es(k[W]):Yt(k[W]),L,ae,H,V,G,q,K),W++}}else if(W>se)for(;W<=J;)We(C[W],H,V,!0),W++;else{const ne=W,ae=W,Ee=new Map;for(W=ae;W<=se;W++){const Pt=k[W]=K?Es(k[W]):Yt(k[W]);Pt.key!=null&&Ee.set(Pt.key,W)}let ye,_t=0;const rt=se-ae+1;let ft=!1,yt=0;const ds=new Array(rt);for(W=0;W<rt;W++)ds[W]=0;for(W=ne;W<=J;W++){const Pt=C[W];if(_t>=rt){We(Pt,H,V,!0);continue}let nn;if(Pt.key!=null)nn=Ee.get(Pt.key);else for(ye=ae;ye<=se;ye++)if(ds[ye-ae]===0&&mn(Pt,k[ye])){nn=ye;break}nn===void 0?We(Pt,H,V,!0):(ds[nn-ae]=W+1,nn>=yt?yt=nn:ft=!0,P(Pt,k[nn],L,null,H,V,G,q,K),_t++)}const ki=ft?pI(ds):qi;for(ye=ki.length-1,W=rt-1;W>=0;W--){const Pt=ae+W,nn=k[Pt],Ri=Pt+1<ie?k[Pt+1].el:j;ds[W]===0?P(null,nn,L,Ri,H,V,G,q,K):ft&&(ye<0||W!==ki[ye]?tn(nn,L,Ri,2):ye--)}}},tn=(C,k,L,j,H=null)=>{const{el:V,type:G,transition:q,children:K,shapeFlag:W}=C;if(W&6){tn(C.component.subTree,k,L,j);return}if(W&128){C.suspense.move(k,L,j);return}if(W&64){G.move(C,k,L,te);return}if(G===Et){s(V,k,L);for(let J=0;J<K.length;J++)tn(K[J],k,L,j);s(C.anchor,k,L);return}if(G===Zr){b(C,k,L);return}if(j!==2&&W&1&&q)if(j===0)q.beforeEnter(V),s(V,k,L),ot(()=>q.enter(V),H);else{const{leave:J,delayLeave:se,afterLeave:ne}=q,ae=()=>s(V,k,L),Ee=()=>{J(V,()=>{ae(),ne&&ne()})};se?se(V,ae,Ee):Ee()}else s(V,k,L)},We=(C,k,L,j=!1,H=!1)=>{const{type:V,props:G,ref:q,children:K,dynamicChildren:W,shapeFlag:ie,patchFlag:J,dirs:se,cacheIndex:ne}=C;if(J===-2&&(H=!1),q!=null&&Qa(q,null,L,C,!0),ne!=null&&(k.renderCache[ne]=void 0),ie&256){k.ctx.deactivate(C);return}const ae=ie&1&&se,Ee=!xs(C);let ye;if(Ee&&(ye=G&&G.onVnodeBeforeUnmount)&&Ut(ye,k,C),ie&6)An(C.component,L,j);else{if(ie&128){C.suspense.unmount(L,j);return}ae&&Rn(C,null,k,"beforeUnmount"),ie&64?C.type.remove(C,k,L,te,j):W&&!W.hasOnce&&(V!==Et||J>0&&J&64)?Ft(W,k,L,!1,!0):(V===Et&&J&384||!H&&ie&16)&&Ft(K,k,L),j&&Ke(C)}(Ee&&(ye=G&&G.onVnodeUnmounted)||ae)&&ot(()=>{ye&&Ut(ye,k,C),ae&&Rn(C,null,k,"unmounted")},L)},Ke=C=>{const{type:k,el:L,anchor:j,transition:H}=C;if(k===Et){hs(L,j);return}if(k===Zr){R(C);return}const V=()=>{i(L),H&&!H.persisted&&H.afterLeave&&H.afterLeave()};if(C.shapeFlag&1&&H&&!H.persisted){const{leave:G,delayLeave:q}=H,K=()=>G(L,V);q?q(C.el,V,K):K()}else V()},hs=(C,k)=>{let L;for(;C!==k;)L=g(C),i(C),C=L;i(k)},An=(C,k,L)=>{const{bum:j,scope:H,job:V,subTree:G,um:q,m:K,a:W}=C;tl(K),tl(W),j&&Yi(j),H.stop(),V&&(V.flags|=8,We(G,C,k,L)),q&&ot(q,k),ot(()=>{C.isUnmounted=!0},k),k&&k.pendingBranch&&!k.isUnmounted&&C.asyncDep&&!C.asyncResolved&&C.suspenseId===k.pendingId&&(k.deps--,k.deps===0&&k.resolve())},Ft=(C,k,L,j=!1,H=!1,V=0)=>{for(let G=V;G<C.length;G++)We(C[G],k,L,j,H)},B=C=>{if(C.shapeFlag&6)return B(C.component.subTree);if(C.shapeFlag&128)return C.suspense.next();const k=g(C.anchor||C.el),L=k&&k[Sy];return L?g(L):k};let Q=!1;const X=(C,k,L)=>{C==null?k._vnode&&We(k._vnode,null,null,!0):P(k._vnode||null,C,k,null,null,null,L),k._vnode=C,Q||(Q=!0,Op(),Xa(),Q=!1)},te={p:P,um:We,m:tn,r:Ke,mt:he,mc:w,pc:Z,pbc:v,n:B,o:t};let de,Re;return e&&([de,Re]=e(te)),{render:X,hydrate:de,createApp:rI(X,de)}}function Jc({type:t,props:e},n){return n==="svg"&&t==="foreignObject"||n==="mathml"&&t==="annotation-xml"&&e&&e.encoding&&e.encoding.includes("html")?void 0:n}function zs({effect:t,job:e},n){n?(t.flags|=32,e.flags|=4):(t.flags&=-33,e.flags&=-5)}function iw(t,e){return(!t||t&&!t.pendingBranch)&&e&&!e.persisted}function dd(t,e,n=!1){const s=t.children,i=e.children;if(re(s)&&re(i))for(let r=0;r<s.length;r++){const o=s[r];let l=i[r];l.shapeFlag&1&&!l.dynamicChildren&&((l.patchFlag<=0||l.patchFlag===32)&&(l=i[r]=Es(i[r]),l.el=o.el),!n&&l.patchFlag!==-2&&dd(o,l)),l.type===ci&&(l.el=o.el)}}function pI(t){const e=t.slice(),n=[0];let s,i,r,o,l;const c=t.length;for(s=0;s<c;s++){const u=t[s];if(u!==0){if(i=n[n.length-1],t[i]<u){e[s]=i,n.push(s);continue}for(r=0,o=n.length-1;r<o;)l=r+o>>1,t[n[l]]<u?r=l+1:o=l;u<t[n[r]]&&(r>0&&(e[s]=n[r-1]),n[r]=s)}}for(r=n.length,o=n[r-1];r-- >0;)n[r]=o,o=e[o];return n}function rw(t){const e=t.subTree.component;if(e)return e.asyncDep&&!e.asyncResolved?e:rw(e)}function tl(t){if(t)for(let e=0;e<t.length;e++)t[e].flags|=8}const gI=Symbol.for("v-scx"),mI=()=>ht(gI);function _I(t,e){return Zl(t,null,e)}function yI(t,e){return Zl(t,null,{flush:"sync"})}function li(t,e,n){return Zl(t,e,n)}function Zl(t,e,n=Te){const{immediate:s,deep:i,flush:r,once:o}=n,l=Ze({},n);let c;if(Lo)if(r==="sync"){const g=mI();c=g.__watcherHandles||(g.__watcherHandles=[])}else if(!e||s)l.once=!0;else{const g=()=>{};return g.stop=Nn,g.resume=Nn,g.pause=Nn,g}const u=tt;l.call=(g,m,I)=>En(g,u,m,I);let h=!1;r==="post"?l.scheduler=g=>{ot(g,u&&u.suspense)}:r!=="sync"&&(h=!0,l.scheduler=(g,m)=>{m?g():ad(g)}),l.augmentJob=g=>{e&&(g.flags|=4),h&&(g.flags|=2,u&&(g.id=u.uid,g.i=u))};const f=RC(t,e,l);return c&&c.push(f),f}function wI(t,e,n){const s=this.proxy,i=He(t)?t.includes(".")?ow(s,t):()=>s[t]:t.bind(s,s);let r;ce(e)?r=e:(r=e.handler,n=e);const o=Do(this),l=Zl(i,r.bind(s),n);return o(),l}function ow(t,e){const n=e.split(".");return()=>{let s=t;for(let i=0;i<n.length&&s;i++)s=s[n[i]];return s}}function rH(t,e,n=Te){const s=Ei(),i=un(e),r=as(e),o=aw(t,e),l=bC((c,u)=>{let h,f=Te,g;return yI(()=>{const m=t[e];Ht(h,m)&&(h=m,u())}),{get(){return c(),n.get?n.get(h):h},set(m){const I=n.set?n.set(m):m;if(!Ht(I,h)&&!(f!==Te&&Ht(m,f)))return;const P=s.vnode.props;P&&(e in P||i in P||r in P)&&(`onUpdate:${e}`in P||`onUpdate:${i}`in P||`onUpdate:${r}`in P)||(h=m,u()),s.emit(`update:${e}`,I),Ht(m,I)&&Ht(m,f)&&!Ht(I,g)&&u(),f=m,g=I}}});return l[Symbol.iterator]=()=>{let c=0;return{next(){return c<2?{value:c++?o||Te:l,done:!1}:{done:!0}}}},l}const aw=(t,e)=>e==="modelValue"||e==="model-value"?t.modelModifiers:t[`${e}Modifiers`]||t[`${un(e)}Modifiers`]||t[`${as(e)}Modifiers`];function vI(t,e,...n){if(t.isUnmounted)return;const s=t.vnode.props||Te;let i=n;const r=e.startsWith("update:"),o=r&&aw(s,e.slice(7));o&&(o.trim&&(i=n.map(h=>He(h)?h.trim():h)),o.number&&(i=n.map(Lu)));let l,c=s[l=jc(e)]||s[l=jc(un(e))];!c&&r&&(c=s[l=jc(as(e))]),c&&En(c,t,6,i);const u=s[l+"Once"];if(u){if(!t.emitted)t.emitted={};else if(t.emitted[l])return;t.emitted[l]=!0,En(u,t,6,i)}}function lw(t,e,n=!1){const s=e.emitsCache,i=s.get(t);if(i!==void 0)return i;const r=t.emits;let o={},l=!1;if(!ce(t)){const c=u=>{const h=lw(u,e,!0);h&&(l=!0,Ze(o,h))};!n&&e.mixins.length&&e.mixins.forEach(c),t.extends&&c(t.extends),t.mixins&&t.mixins.forEach(c)}return!r&&!l?(Le(t)&&s.set(t,null),null):(re(r)?r.forEach(c=>o[c]=null):Ze(o,r),Le(t)&&s.set(t,o),o)}function ec(t,e){return!t||!Po(e)?!1:(e=e.slice(2).replace(/Once$/,""),Ce(t,e[0].toLowerCase()+e.slice(1))||Ce(t,as(e))||Ce(t,e))}function Qc(t){const{type:e,vnode:n,proxy:s,withProxy:i,propsOptions:[r],slots:o,attrs:l,emit:c,render:u,renderCache:h,props:f,data:g,setupState:m,ctx:I,inheritAttrs:P}=t,D=Ja(t);let M,x;try{if(n.shapeFlag&4){const R=i||s,N=R;M=Yt(u.call(N,R,h,f,m,g,I)),x=l}else{const R=e;M=Yt(R.length>1?R(f,{attrs:l,slots:o,emit:c}):R(f,null)),x=e.props?l:EI(l)}}catch(R){eo.length=0,yr(R,t,1),M=De(et)}let b=M;if(x&&P!==!1){const R=Object.keys(x),{shapeFlag:N}=b;R.length&&N&7&&(r&&R.some(Yh)&&(x=TI(x,r)),b=ss(b,x,!1,!0))}return n.dirs&&(b=ss(b,null,!1,!0),b.dirs=b.dirs?b.dirs.concat(n.dirs):n.dirs),n.transition&&ar(b,n.transition),M=b,Ja(D),M}function bI(t,e=!0){let n;for(let s=0;s<t.length;s++){const i=t[s];if(cr(i)){if(i.type!==et||i.children==="v-if"){if(n)return;n=i}}else return}return n}const EI=t=>{let e;for(const n in t)(n==="class"||n==="style"||Po(n))&&((e||(e={}))[n]=t[n]);return e},TI=(t,e)=>{const n={};for(const s in t)(!Yh(s)||!(s.slice(9)in e))&&(n[s]=t[s]);return n};function CI(t,e,n){const{props:s,children:i,component:r}=t,{props:o,children:l,patchFlag:c}=e,u=r.emitsOptions;if(e.dirs||e.transition)return!0;if(n&&c>=0){if(c&1024)return!0;if(c&16)return s?Vp(s,o,u):!!o;if(c&8){const h=e.dynamicProps;for(let f=0;f<h.length;f++){const g=h[f];if(o[g]!==s[g]&&!ec(u,g))return!0}}}else return(i||l)&&(!l||!l.$stable)?!0:s===o?!1:s?o?Vp(s,o,u):!0:!!o;return!1}function Vp(t,e,n){const s=Object.keys(e);if(s.length!==Object.keys(t).length)return!0;for(let i=0;i<s.length;i++){const r=s[i];if(e[r]!==t[r]&&!ec(n,r))return!0}return!1}function fd({vnode:t,parent:e},n){for(;e;){const s=e.subTree;if(s.suspense&&s.suspense.activeBranch===t&&(s.el=t.el),s===t)(t=e.vnode).el=n,e=e.parent;else break}}const nl=t=>t.__isSuspense;let zu=0;const II={name:"Suspense",__isSuspense:!0,process(t,e,n,s,i,r,o,l,c,u){if(t==null)SI(e,n,s,i,r,o,l,c,u);else{if(r&&r.deps>0&&!t.suspense.isInFallback){e.suspense=t.suspense,e.suspense.vnode=e,e.el=t.el;return}AI(t,e,n,s,i,o,l,c,u)}},hydrate:kI,normalize:RI},pd=II;function yo(t,e){const n=t.props&&t.props[e];ce(n)&&n()}function SI(t,e,n,s,i,r,o,l,c){const{p:u,o:{createElement:h}}=c,f=h("div"),g=t.suspense=cw(t,i,s,e,f,n,r,o,l,c);u(null,g.pendingBranch=t.ssContent,f,null,s,g,r,o),g.deps>0?(yo(t,"onPending"),yo(t,"onFallback"),u(null,t.ssFallback,e,n,s,null,r,o),Ji(g,t.ssFallback)):g.resolve(!1,!0)}function AI(t,e,n,s,i,r,o,l,{p:c,um:u,o:{createElement:h}}){const f=e.suspense=t.suspense;f.vnode=e,e.el=t.el;const g=e.ssContent,m=e.ssFallback,{activeBranch:I,pendingBranch:P,isInFallback:D,isHydrating:M}=f;if(P)f.pendingBranch=g,mn(g,P)?(c(P,g,f.hiddenContainer,null,i,f,r,o,l),f.deps<=0?f.resolve():D&&(M||(c(I,m,n,s,i,null,r,o,l),Ji(f,m)))):(f.pendingId=zu++,M?(f.isHydrating=!1,f.activeBranch=P):u(P,i,f),f.deps=0,f.effects.length=0,f.hiddenContainer=h("div"),D?(c(null,g,f.hiddenContainer,null,i,f,r,o,l),f.deps<=0?f.resolve():(c(I,m,n,s,i,null,r,o,l),Ji(f,m))):I&&mn(g,I)?(c(I,g,n,s,i,f,r,o,l),f.resolve(!0)):(c(null,g,f.hiddenContainer,null,i,f,r,o,l),f.deps<=0&&f.resolve()));else if(I&&mn(g,I))c(I,g,n,s,i,f,r,o,l),Ji(f,g);else if(yo(e,"onPending"),f.pendingBranch=g,g.shapeFlag&512?f.pendingId=g.component.suspenseId:f.pendingId=zu++,c(null,g,f.hiddenContainer,null,i,f,r,o,l),f.deps<=0)f.resolve();else{const{timeout:x,pendingId:b}=f;x>0?setTimeout(()=>{f.pendingId===b&&f.fallback(m)},x):x===0&&f.fallback(m)}}function cw(t,e,n,s,i,r,o,l,c,u,h=!1){const{p:f,m:g,um:m,n:I,o:{parentNode:P,remove:D}}=u;let M;const x=PI(t);x&&e&&e.pendingBranch&&(M=e.pendingId,e.deps++);const b=t.props?J_(t.props.timeout):void 0,R=r,N={vnode:t,parent:e,parentComponent:n,namespace:o,container:s,hiddenContainer:i,deps:0,pendingId:zu++,timeout:typeof b=="number"?b:-1,activeBranch:null,pendingBranch:null,isInFallback:!h,isHydrating:h,isUnmounted:!1,effects:[],resolve(F=!1,T=!1){const{vnode:w,activeBranch:y,pendingBranch:v,pendingId:A,effects:S,parentComponent:E,container:he}=N;let pe=!1;N.isHydrating?N.isHydrating=!1:F||(pe=y&&v.transition&&v.transition.mode==="out-in",pe&&(y.transition.afterLeave=()=>{A===N.pendingId&&(g(v,he,r===R?I(y):r,0),Hu(S))}),y&&(P(y.el)===he&&(r=I(y)),m(y,E,N,!0)),pe||g(v,he,r,0)),Ji(N,v),N.pendingBranch=null,N.isInFallback=!1;let ee=N.parent,le=!1;for(;ee;){if(ee.pendingBranch){ee.effects.push(...S),le=!0;break}ee=ee.parent}!le&&!pe&&Hu(S),N.effects=[],x&&e&&e.pendingBranch&&M===e.pendingId&&(e.deps--,e.deps===0&&!T&&e.resolve()),yo(w,"onResolve")},fallback(F){if(!N.pendingBranch)return;const{vnode:T,activeBranch:w,parentComponent:y,container:v,namespace:A}=N;yo(T,"onFallback");const S=I(w),E=()=>{N.isInFallback&&(f(null,F,v,S,y,null,A,l,c),Ji(N,F))},he=F.transition&&F.transition.mode==="out-in";he&&(w.transition.afterLeave=E),N.isInFallback=!0,m(w,y,null,!0),he||E()},move(F,T,w){N.activeBranch&&g(N.activeBranch,F,T,w),N.container=F},next(){return N.activeBranch&&I(N.activeBranch)},registerDep(F,T,w){const y=!!N.pendingBranch;y&&N.deps++;const v=F.vnode.el;F.asyncDep.catch(A=>{yr(A,F,0)}).then(A=>{if(F.isUnmounted||N.isUnmounted||N.pendingId!==F.suspenseId)return;F.asyncResolved=!0;const{vnode:S}=F;Yu(F,A,!1),v&&(S.el=v);const E=!v&&F.subTree.el;T(F,S,P(v||F.subTree.el),v?null:I(F.subTree),N,o,w),E&&D(E),fd(F,S.el),y&&--N.deps===0&&N.resolve()})},unmount(F,T){N.isUnmounted=!0,N.activeBranch&&m(N.activeBranch,n,F,T),N.pendingBranch&&m(N.pendingBranch,n,F,T)}};return N}function kI(t,e,n,s,i,r,o,l,c){const u=e.suspense=cw(e,s,n,t.parentNode,document.createElement("div"),null,i,r,o,l,!0),h=c(t,u.pendingBranch=e.ssContent,n,u,r,o);return u.deps===0&&u.resolve(!1,!0),h}function RI(t){const{shapeFlag:e,children:n}=t,s=e&32;t.ssContent=Wp(s?n.default:n),t.ssFallback=s?Wp(n.fallback):De(et)}function Wp(t){let e;if(ce(t)){const n=lr&&t._c;n&&(t._d=!1,Gt()),t=t(),n&&(t._d=!0,e=Bt,hw())}return re(t)&&(t=bI(t)),t=Yt(t),e&&!t.dynamicChildren&&(t.dynamicChildren=e.filter(n=>n!==t)),t}function uw(t,e){e&&e.pendingBranch?re(t)?e.effects.push(...t):e.effects.push(t):Hu(t)}function Ji(t,e){t.activeBranch=e;const{vnode:n,parentComponent:s}=t;let i=e.el;for(;!i&&e.component;)e=e.component.subTree,i=e.el;n.el=i,s&&s.subTree===n&&(s.vnode.el=i,fd(s,i))}function PI(t){const e=t.props&&t.props.suspensible;return e!=null&&e!==!1}const Et=Symbol.for("v-fgt"),ci=Symbol.for("v-txt"),et=Symbol.for("v-cmt"),Zr=Symbol.for("v-stc"),eo=[];let Bt=null;function Gt(t=!1){eo.push(Bt=t?null:[])}function hw(){eo.pop(),Bt=eo[eo.length-1]||null}let lr=1;function Kp(t){lr+=t,t<0&&Bt&&(Bt.hasOnce=!0)}function dw(t){return t.dynamicChildren=lr>0?Bt||qi:null,hw(),lr>0&&Bt&&Bt.push(t),t}function gd(t,e,n,s,i,r){return dw(md(t,e,n,s,i,r,!0))}function zn(t,e,n,s,i){return dw(De(t,e,n,s,i,!0))}function cr(t){return t?t.__v_isVNode===!0:!1}function mn(t,e){return t.type===e.type&&t.key===e.key}const fw=({key:t})=>t??null,$a=({ref:t,ref_key:e,ref_for:n})=>(typeof t=="number"&&(t=""+t),t!=null?He(t)||ut(t)||ce(t)?{i:it,r:t,k:e,f:!!n}:t:null);function md(t,e=null,n=null,s=0,i=null,r=t===Et?0:1,o=!1,l=!1){const c={__v_isVNode:!0,__v_skip:!0,type:t,props:e,key:e&&fw(e),ref:e&&$a(e),scopeId:Iy,slotScopeIds:null,children:n,component:null,suspense:null,ssContent:null,ssFallback:null,dirs:null,transition:null,el:null,anchor:null,target:null,targetStart:null,targetAnchor:null,staticCount:0,shapeFlag:r,patchFlag:s,dynamicProps:i,dynamicChildren:null,appContext:null,ctx:it};return l?(yd(c,n),r&128&&t.normalize(c)):n&&(c.shapeFlag|=He(n)?8:16),lr>0&&!o&&Bt&&(c.patchFlag>0||r&6)&&c.patchFlag!==32&&Bt.push(c),c}const De=OI;function OI(t,e=null,n=null,s=0,i=null,r=!1){if((!t||t===By)&&(t=et),cr(t)){const l=ss(t,e,!0);return n&&yd(l,n),lr>0&&!r&&Bt&&(l.shapeFlag&6?Bt[Bt.indexOf(t)]=l:Bt.push(l)),l.patchFlag=-2,l}if($I(t)&&(t=t.__vccOpts),e){e=pw(e);let{class:l,style:c}=e;l&&!He(l)&&(e.class=ql(l)),Le(c)&&(id(c)&&!re(c)&&(c=Ze({},c)),e.style=Kl(c))}const o=He(t)?1:nl(t)?128:Ay(t)?64:Le(t)?4:ce(t)?2:0;return md(t,e,n,s,i,o,r,!0)}function pw(t){return t?id(t)||Xy(t)?Ze({},t):t:null}function ss(t,e,n=!1,s=!1){const{props:i,ref:r,patchFlag:o,children:l,transition:c}=t,u=e?gw(i||{},e):i,h={__v_isVNode:!0,__v_skip:!0,type:t.type,props:u,key:u&&fw(u),ref:e&&e.ref?n&&r?re(r)?r.concat($a(e)):[r,$a(e)]:$a(e):r,scopeId:t.scopeId,slotScopeIds:t.slotScopeIds,children:l,target:t.target,targetStart:t.targetStart,targetAnchor:t.targetAnchor,staticCount:t.staticCount,shapeFlag:t.shapeFlag,patchFlag:e&&t.type!==Et?o===-1?16:o|16:o,dynamicProps:t.dynamicProps,dynamicChildren:t.dynamicChildren,appContext:t.appContext,dirs:t.dirs,transition:c,component:t.component,suspense:t.suspense,ssContent:t.ssContent&&ss(t.ssContent),ssFallback:t.ssFallback&&ss(t.ssFallback),el:t.el,anchor:t.anchor,ctx:t.ctx,ce:t.ce};return c&&s&&ar(h,c.clone(h)),h}function _d(t=" ",e=0){return De(ci,null,t,e)}function oH(t,e){const n=De(Zr,null,t);return n.staticCount=e,n}function NI(t="",e=!1){return e?(Gt(),zn(et,null,t)):De(et,null,t)}function Yt(t){return t==null||typeof t=="boolean"?De(et):re(t)?De(Et,null,t.slice()):typeof t=="object"?Es(t):De(ci,null,String(t))}function Es(t){return t.el===null&&t.patchFlag!==-1||t.memo?t:ss(t)}function yd(t,e){let n=0;const{shapeFlag:s}=t;if(e==null)e=null;else if(re(e))n=16;else if(typeof e=="object")if(s&65){const i=e.default;i&&(i._c&&(i._d=!1),yd(t,i()),i._c&&(i._d=!0));return}else{n=32;const i=e._;!i&&!Xy(e)?e._ctx=it:i===3&&it&&(it.slots._===1?e._=1:(e._=2,t.patchFlag|=1024))}else ce(e)?(e={default:e,_ctx:it},n=32):(e=String(e),s&64?(n=16,e=[_d(e)]):n=8);t.children=e,t.shapeFlag|=n}function gw(...t){const e={};for(let n=0;n<t.length;n++){const s=t[n];for(const i in s)if(i==="class")e.class!==s.class&&(e.class=ql([e.class,s.class]));else if(i==="style")e.style=Kl([e.style,s.style]);else if(Po(i)){const r=e[i],o=s[i];o&&r!==o&&!(re(r)&&r.includes(o))&&(e[i]=r?[].concat(r,o):o)}else i!==""&&(e[i]=s[i])}return e}function Ut(t,e,n,s=null){En(t,e,7,[n,s])}const xI=qy();let DI=0;function LI(t,e,n){const s=t.type,i=(e?e.appContext:t.appContext)||xI,r={uid:DI++,vnode:t,type:s,parent:e,appContext:i,root:null,next:null,subTree:null,effect:null,update:null,job:null,scope:new ny(!0),render:null,proxy:null,exposed:null,exposeProxy:null,withProxy:null,provides:e?e.provides:Object.create(i.provides),ids:e?e.ids:["",0,0],accessCache:null,renderCache:[],components:null,directives:null,propsOptions:Qy(s,i),emitsOptions:lw(s,i),emit:null,emitted:null,propsDefaults:Te,inheritAttrs:s.inheritAttrs,ctx:Te,data:Te,props:Te,attrs:Te,slots:Te,refs:Te,setupState:Te,setupContext:null,suspense:n,suspenseId:n?n.pendingId:0,asyncDep:null,asyncResolved:!1,isMounted:!1,isUnmounted:!1,isDeactivated:!1,bc:null,c:null,bm:null,m:null,bu:null,u:null,um:null,bum:null,da:null,a:null,rtg:null,rtc:null,ec:null,sp:null};return r.ctx={_:r},r.root=e?e.root:r,r.emit=vI.bind(null,r),t.ce&&t.ce(r),r}let tt=null;const Ei=()=>tt||it;let sl,Gu;{const t=Q_(),e=(n,s)=>{let i;return(i=t[n])||(i=t[n]=[]),i.push(s),r=>{i.length>1?i.forEach(o=>o(r)):i[0](r)}};sl=e("__VUE_INSTANCE_SETTERS__",n=>tt=n),Gu=e("__VUE_SSR_SETTERS__",n=>Lo=n)}const Do=t=>{const e=tt;return sl(t),t.scope.on(),()=>{t.scope.off(),sl(e)}},qp=()=>{tt&&tt.scope.off(),sl(null)};function mw(t){return t.vnode.shapeFlag&4}let Lo=!1;function MI(t,e=!1,n=!1){e&&Gu(e);const{props:s,children:i}=t.vnode,r=mw(t);oI(t,s,r,e),uI(t,i,n);const o=r?FI(t,e):void 0;return e&&Gu(!1),o}function FI(t,e){const n=t.type;t.accessCache=Object.create(null),t.proxy=new Proxy(t.ctx,QC);const{setup:s}=n;if(s){const i=t.setupContext=s.length>1?yw(t):null,r=Do(t);$s();const o=No(s,t,0,[t.props,i]);if(Hs(),r(),z_(o)){if(xs(t)||cd(t),o.then(qp,qp),e)return o.then(l=>{Yu(t,l,e)}).catch(l=>{yr(l,t,0)});t.asyncDep=o}else Yu(t,o,e)}else _w(t,e)}function Yu(t,e,n){ce(e)?t.type.__ssrInlineRender?t.ssrRender=e:t.render=e:Le(e)&&(t.setupState=by(e)),_w(t,n)}let zp;function _w(t,e,n){const s=t.type;if(!t.render){if(!e&&zp&&!s.render){const i=s.template||ud(t).template;if(i){const{isCustomElement:r,compilerOptions:o}=t.appContext.config,{delimiters:l,compilerOptions:c}=s,u=Ze(Ze({isCustomElement:r,delimiters:l},o),c);s.render=zp(i,u)}}t.render=s.render||Nn}{const i=Do(t);$s();try{ZC(t)}finally{Hs(),i()}}}const UI={get(t,e){return At(t,"get",""),t[e]}};function yw(t){const e=n=>{t.exposed=n||{}};return{attrs:new Proxy(t.attrs,UI),slots:t.slots,emit:t.emit,expose:e}}function tc(t){return t.exposed?t.exposeProxy||(t.exposeProxy=new Proxy(by(Uu(t.exposed)),{get(e,n){if(n in e)return e[n];if(n in Qr)return Qr[n](t)},has(e,n){return n in e||n in Qr}})):t.proxy}function Xu(t,e=!0){return ce(t)?t.displayName||t.name:t.name||e&&t.__name}function $I(t){return ce(t)&&"__vccOpts"in t}const an=(t,e)=>AC(t,e,Lo);function jt(t,e,n){const s=arguments.length;return s===2?Le(e)&&!re(e)?cr(e)?De(t,null,[e]):De(t,e):De(t,null,e):(s>3?n=Array.prototype.slice.call(arguments,2):s===3&&cr(n)&&(n=[n]),De(t,e,n))}const ww="3.5.8";/**
* @vue/runtime-dom v3.5.8
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/let Ju;const Gp=typeof window<"u"&&window.trustedTypes;if(Gp)try{Ju=Gp.createPolicy("vue",{createHTML:t=>t})}catch{}const vw=Ju?t=>Ju.createHTML(t):t=>t,HI="http://www.w3.org/2000/svg",BI="http://www.w3.org/1998/Math/MathML",Wn=typeof document<"u"?document:null,Yp=Wn&&Wn.createElement("template"),jI={insert:(t,e,n)=>{e.insertBefore(t,n||null)},remove:t=>{const e=t.parentNode;e&&e.removeChild(t)},createElement:(t,e,n,s)=>{const i=e==="svg"?Wn.createElementNS(HI,t):e==="mathml"?Wn.createElementNS(BI,t):n?Wn.createElement(t,{is:n}):Wn.createElement(t);return t==="select"&&s&&s.multiple!=null&&i.setAttribute("multiple",s.multiple),i},createText:t=>Wn.createTextNode(t),createComment:t=>Wn.createComment(t),setText:(t,e)=>{t.nodeValue=e},setElementText:(t,e)=>{t.textContent=e},parentNode:t=>t.parentNode,nextSibling:t=>t.nextSibling,querySelector:t=>Wn.querySelector(t),setScopeId(t,e){t.setAttribute(e,"")},insertStaticContent(t,e,n,s,i,r){const o=n?n.previousSibling:e.lastChild;if(i&&(i===r||i.nextSibling))for(;e.insertBefore(i.cloneNode(!0),n),!(i===r||!(i=i.nextSibling)););else{Yp.innerHTML=vw(s==="svg"?`<svg>${t}</svg>`:s==="mathml"?`<math>${t}</math>`:t);const l=Yp.content;if(s==="svg"||s==="mathml"){const c=l.firstChild;for(;c.firstChild;)l.appendChild(c.firstChild);l.removeChild(c)}e.insertBefore(l,n)}return[o?o.nextSibling:e.firstChild,n?n.previousSibling:e.lastChild]}},_s="transition",Hr="animation",wo=Symbol("_vtc"),bw={name:String,type:String,css:{type:Boolean,default:!0},duration:[String,Number,Object],enterFromClass:String,enterActiveClass:String,enterToClass:String,appearFromClass:String,appearActiveClass:String,appearToClass:String,leaveFromClass:String,leaveActiveClass:String,leaveToClass:String},VI=Ze({},Ry,bw),WI=t=>(t.displayName="Transition",t.props=VI,t),Ew=WI((t,{slots:e})=>jt(FC,KI(t),e)),Gs=(t,e=[])=>{re(t)?t.forEach(n=>n(...e)):t&&t(...e)},Xp=t=>t?re(t)?t.some(e=>e.length>1):t.length>1:!1;function KI(t){const e={};for(const S in t)S in bw||(e[S]=t[S]);if(t.css===!1)return e;const{name:n="v",type:s,duration:i,enterFromClass:r=`${n}-enter-from`,enterActiveClass:o=`${n}-enter-active`,enterToClass:l=`${n}-enter-to`,appearFromClass:c=r,appearActiveClass:u=o,appearToClass:h=l,leaveFromClass:f=`${n}-leave-from`,leaveActiveClass:g=`${n}-leave-active`,leaveToClass:m=`${n}-leave-to`}=t,I=qI(i),P=I&&I[0],D=I&&I[1],{onBeforeEnter:M,onEnter:x,onEnterCancelled:b,onLeave:R,onLeaveCancelled:N,onBeforeAppear:F=M,onAppear:T=x,onAppearCancelled:w=b}=e,y=(S,E,he)=>{Ys(S,E?h:l),Ys(S,E?u:o),he&&he()},v=(S,E)=>{S._isLeaving=!1,Ys(S,f),Ys(S,m),Ys(S,g),E&&E()},A=S=>(E,he)=>{const pe=S?T:x,ee=()=>y(E,S,he);Gs(pe,[E,ee]),Jp(()=>{Ys(E,S?c:r),ys(E,S?h:l),Xp(pe)||Qp(E,s,P,ee)})};return Ze(e,{onBeforeEnter(S){Gs(M,[S]),ys(S,r),ys(S,o)},onBeforeAppear(S){Gs(F,[S]),ys(S,c),ys(S,u)},onEnter:A(!1),onAppear:A(!0),onLeave(S,E){S._isLeaving=!0;const he=()=>v(S,E);ys(S,f),ys(S,g),YI(),Jp(()=>{S._isLeaving&&(Ys(S,f),ys(S,m),Xp(R)||Qp(S,s,D,he))}),Gs(R,[S,he])},onEnterCancelled(S){y(S,!1),Gs(b,[S])},onAppearCancelled(S){y(S,!0),Gs(w,[S])},onLeaveCancelled(S){v(S),Gs(N,[S])}})}function qI(t){if(t==null)return null;if(Le(t))return[Zc(t.enter),Zc(t.leave)];{const e=Zc(t);return[e,e]}}function Zc(t){return J_(t)}function ys(t,e){e.split(/\s+/).forEach(n=>n&&t.classList.add(n)),(t[wo]||(t[wo]=new Set)).add(e)}function Ys(t,e){e.split(/\s+/).forEach(s=>s&&t.classList.remove(s));const n=t[wo];n&&(n.delete(e),n.size||(t[wo]=void 0))}function Jp(t){requestAnimationFrame(()=>{requestAnimationFrame(t)})}let zI=0;function Qp(t,e,n,s){const i=t._endId=++zI,r=()=>{i===t._endId&&s()};if(n!=null)return setTimeout(r,n);const{type:o,timeout:l,propCount:c}=GI(t,e);if(!o)return s();const u=o+"end";let h=0;const f=()=>{t.removeEventListener(u,g),r()},g=m=>{m.target===t&&++h>=c&&f()};setTimeout(()=>{h<c&&f()},l+1),t.addEventListener(u,g)}function GI(t,e){const n=window.getComputedStyle(t),s=I=>(n[I]||"").split(", "),i=s(`${_s}Delay`),r=s(`${_s}Duration`),o=Zp(i,r),l=s(`${Hr}Delay`),c=s(`${Hr}Duration`),u=Zp(l,c);let h=null,f=0,g=0;e===_s?o>0&&(h=_s,f=o,g=r.length):e===Hr?u>0&&(h=Hr,f=u,g=c.length):(f=Math.max(o,u),h=f>0?o>u?_s:Hr:null,g=h?h===_s?r.length:c.length:0);const m=h===_s&&/\b(transform|all)(,|$)/.test(s(`${_s}Property`).toString());return{type:h,timeout:f,propCount:g,hasTransform:m}}function Zp(t,e){for(;t.length<e.length;)t=t.concat(t);return Math.max(...e.map((n,s)=>eg(n)+eg(t[s])))}function eg(t){return t==="auto"?0:Number(t.slice(0,-1).replace(",","."))*1e3}function YI(){return document.body.offsetHeight}function XI(t,e,n){const s=t[wo];s&&(e=(e?[e,...s]:[...s]).join(" ")),e==null?t.removeAttribute("class"):n?t.setAttribute("class",e):t.className=e}const il=Symbol("_vod"),Tw=Symbol("_vsh"),aH={beforeMount(t,{value:e},{transition:n}){t[il]=t.style.display==="none"?"":t.style.display,n&&e?n.beforeEnter(t):Br(t,e)},mounted(t,{value:e},{transition:n}){n&&e&&n.enter(t)},updated(t,{value:e,oldValue:n},{transition:s}){!e!=!n&&(s?e?(s.beforeEnter(t),Br(t,!0),s.enter(t)):s.leave(t,()=>{Br(t,!1)}):Br(t,e))},beforeUnmount(t,{value:e}){Br(t,e)}};function Br(t,e){t.style.display=e?t[il]:"none",t[Tw]=!e}const JI=Symbol(""),QI=/(^|;)\s*display\s*:/;function ZI(t,e,n){const s=t.style,i=He(n);let r=!1;if(n&&!i){if(e)if(He(e))for(const o of e.split(";")){const l=o.slice(0,o.indexOf(":")).trim();n[l]==null&&Ha(s,l,"")}else for(const o in e)n[o]==null&&Ha(s,o,"");for(const o in n)o==="display"&&(r=!0),Ha(s,o,n[o])}else if(i){if(e!==n){const o=s[JI];o&&(n+=";"+o),s.cssText=n,r=QI.test(n)}}else e&&t.removeAttribute("style");il in t&&(t[il]=r?s.display:"",t[Tw]&&(s.display="none"))}const tg=/\s*!important$/;function Ha(t,e,n){if(re(n))n.forEach(s=>Ha(t,e,s));else if(n==null&&(n=""),e.startsWith("--"))t.setProperty(e,n);else{const s=eS(t,e);tg.test(n)?t.setProperty(as(s),n.replace(tg,""),"important"):t[s]=n}}const ng=["Webkit","Moz","ms"],eu={};function eS(t,e){const n=eu[e];if(n)return n;let s=un(e);if(s!=="filter"&&s in t)return eu[e]=s;s=Wl(s);for(let i=0;i<ng.length;i++){const r=ng[i]+s;if(r in t)return eu[e]=r}return e}const sg="http://www.w3.org/1999/xlink";function ig(t,e,n,s,i,r=GT(e)){s&&e.startsWith("xlink:")?n==null?t.removeAttributeNS(sg,e.slice(6,e.length)):t.setAttributeNS(sg,e,n):n==null||r&&!Z_(n)?t.removeAttribute(e):t.setAttribute(e,r?"":Us(n)?String(n):n)}function tS(t,e,n,s){if(e==="innerHTML"||e==="textContent"){n!=null&&(t[e]=e==="innerHTML"?vw(n):n);return}const i=t.tagName;if(e==="value"&&i!=="PROGRESS"&&!i.includes("-")){const o=i==="OPTION"?t.getAttribute("value")||"":t.value,l=n==null?t.type==="checkbox"?"on":"":String(n);(o!==l||!("_value"in t))&&(t.value=l),n==null&&t.removeAttribute(e),t._value=n;return}let r=!1;if(n===""||n==null){const o=typeof t[e];o==="boolean"?n=Z_(n):n==null&&o==="string"?(n="",r=!0):o==="number"&&(n=0,r=!0)}try{t[e]=n}catch{}r&&t.removeAttribute(e)}function $i(t,e,n,s){t.addEventListener(e,n,s)}function nS(t,e,n,s){t.removeEventListener(e,n,s)}const rg=Symbol("_vei");function sS(t,e,n,s,i=null){const r=t[rg]||(t[rg]={}),o=r[e];if(s&&o)o.value=s;else{const[l,c]=iS(e);if(s){const u=r[e]=aS(s,i);$i(t,l,u,c)}else o&&(nS(t,l,o,c),r[e]=void 0)}}const og=/(?:Once|Passive|Capture)$/;function iS(t){let e;if(og.test(t)){e={};let s;for(;s=t.match(og);)t=t.slice(0,t.length-s[0].length),e[s[0].toLowerCase()]=!0}return[t[2]===":"?t.slice(3):as(t.slice(2)),e]}let tu=0;const rS=Promise.resolve(),oS=()=>tu||(rS.then(()=>tu=0),tu=Date.now());function aS(t,e){const n=s=>{if(!s._vts)s._vts=Date.now();else if(s._vts<=n.attached)return;En(lS(s,n.value),e,5,[s])};return n.value=t,n.attached=oS(),n}function lS(t,e){if(re(e)){const n=t.stopImmediatePropagation;return t.stopImmediatePropagation=()=>{n.call(t),t._stopped=!0},e.map(s=>i=>!i._stopped&&s&&s(i))}else return e}const ag=t=>t.charCodeAt(0)===111&&t.charCodeAt(1)===110&&t.charCodeAt(2)>96&&t.charCodeAt(2)<123,cS=(t,e,n,s,i,r)=>{const o=i==="svg";e==="class"?XI(t,s,o):e==="style"?ZI(t,n,s):Po(e)?Yh(e)||sS(t,e,n,s,r):(e[0]==="."?(e=e.slice(1),!0):e[0]==="^"?(e=e.slice(1),!1):uS(t,e,s,o))?(tS(t,e,s),!t.tagName.includes("-")&&(e==="value"||e==="checked"||e==="selected")&&ig(t,e,s,o,r,e!=="value")):(e==="true-value"?t._trueValue=s:e==="false-value"&&(t._falseValue=s),ig(t,e,s,o))};function uS(t,e,n,s){if(s)return!!(e==="innerHTML"||e==="textContent"||e in t&&ag(e)&&ce(n));if(e==="spellcheck"||e==="draggable"||e==="translate"||e==="form"||e==="list"&&t.tagName==="INPUT"||e==="type"&&t.tagName==="TEXTAREA")return!1;if(e==="width"||e==="height"){const i=t.tagName;if(i==="IMG"||i==="VIDEO"||i==="CANVAS"||i==="SOURCE")return!1}return ag(e)&&He(n)?!1:!!(e in t||t._isVueCE&&(/[A-Z]/.test(e)||!He(n)))}const lg=t=>{const e=t.props["onUpdate:modelValue"]||!1;return re(e)?n=>Yi(e,n):e};function hS(t){t.target.composing=!0}function cg(t){const e=t.target;e.composing&&(e.composing=!1,e.dispatchEvent(new Event("input")))}const nu=Symbol("_assign"),lH={created(t,{modifiers:{lazy:e,trim:n,number:s}},i){t[nu]=lg(i);const r=s||i.props&&i.props.type==="number";$i(t,e?"change":"input",o=>{if(o.target.composing)return;let l=t.value;n&&(l=l.trim()),r&&(l=Lu(l)),t[nu](l)}),n&&$i(t,"change",()=>{t.value=t.value.trim()}),e||($i(t,"compositionstart",hS),$i(t,"compositionend",cg),$i(t,"change",cg))},mounted(t,{value:e}){t.value=e??""},beforeUpdate(t,{value:e,oldValue:n,modifiers:{lazy:s,trim:i,number:r}},o){if(t[nu]=lg(o),t.composing)return;const l=(r||t.type==="number")&&!/^0\d/.test(t.value)?Lu(t.value):t.value,c=e??"";l!==c&&(document.activeElement===t&&t.type!=="range"&&(s&&e===n||i&&t.value.trim()===c)||(t.value=c))}},dS=["ctrl","shift","alt","meta"],fS={stop:t=>t.stopPropagation(),prevent:t=>t.preventDefault(),self:t=>t.target!==t.currentTarget,ctrl:t=>!t.ctrlKey,shift:t=>!t.shiftKey,alt:t=>!t.altKey,meta:t=>!t.metaKey,left:t=>"button"in t&&t.button!==0,middle:t=>"button"in t&&t.button!==1,right:t=>"button"in t&&t.button!==2,exact:(t,e)=>dS.some(n=>t[`${n}Key`]&&!e.includes(n))},cH=(t,e)=>{const n=t._withMods||(t._withMods={}),s=e.join(".");return n[s]||(n[s]=(i,...r)=>{for(let o=0;o<e.length;o++){const l=fS[e[o]];if(l&&l(i,e))return}return t(i,...r)})},pS={esc:"escape",space:" ",up:"arrow-up",left:"arrow-left",right:"arrow-right",down:"arrow-down",delete:"backspace"},uH=(t,e)=>{const n=t._withKeys||(t._withKeys={}),s=e.join(".");return n[s]||(n[s]=i=>{if(!("key"in i))return;const r=as(i.key);if(e.some(o=>o===r||pS[o]===r))return t(i)})},Cw=Ze({patchProp:cS},jI);let to,ug=!1;function Iw(){return to||(to=dI(Cw))}function gS(){return to=ug?to:fI(Cw),ug=!0,to}const hH=(...t)=>{Iw().render(...t)},mS=(...t)=>{const e=Iw().createApp(...t),{mount:n}=e;return e.mount=s=>{const i=Aw(s);if(!i)return;const r=e._component;!ce(r)&&!r.render&&!r.template&&(r.template=i.innerHTML),i.nodeType===1&&(i.textContent="");const o=n(i,!1,Sw(i));return i instanceof Element&&(i.removeAttribute("v-cloak"),i.setAttribute("data-v-app","")),o},e},_S=(...t)=>{const e=gS().createApp(...t),{mount:n}=e;return e.mount=s=>{const i=Aw(s);if(i)return n(i,!0,Sw(i))},e};function Sw(t){if(t instanceof SVGElement)return"svg";if(typeof MathMLElement=="function"&&t instanceof MathMLElement)return"mathml"}function Aw(t){return He(t)?document.querySelector(t):t}const yS=/"(?:_|\\u0{2}5[Ff]){2}(?:p|\\u0{2}70)(?:r|\\u0{2}72)(?:o|\\u0{2}6[Ff])(?:t|\\u0{2}74)(?:o|\\u0{2}6[Ff])(?:_|\\u0{2}5[Ff]){2}"\s*:/,wS=/"(?:c|\\u0063)(?:o|\\u006[Ff])(?:n|\\u006[Ee])(?:s|\\u0073)(?:t|\\u0074)(?:r|\\u0072)(?:u|\\u0075)(?:c|\\u0063)(?:t|\\u0074)(?:o|\\u006[Ff])(?:r|\\u0072)"\s*:/,vS=/^\s*["[{]|^\s*-?\d{1,16}(\.\d{1,17})?([Ee][+-]?\d+)?\s*$/;function bS(t,e){if(t==="__proto__"||t==="constructor"&&e&&typeof e=="object"&&"prototype"in e){ES(t);return}return e}function ES(t){console.warn(`[destr] Dropping "${t}" key to prevent prototype pollution.`)}function rl(t,e={}){if(typeof t!="string")return t;const n=t.trim();if(t[0]==='"'&&t.endsWith('"')&&!t.includes("\\"))return n.slice(1,-1);if(n.length<=9){const s=n.toLowerCase();if(s==="true")return!0;if(s==="false")return!1;if(s==="undefined")return;if(s==="null")return null;if(s==="nan")return Number.NaN;if(s==="infinity")return Number.POSITIVE_INFINITY;if(s==="-infinity")return Number.NEGATIVE_INFINITY}if(!vS.test(t)){if(e.strict)throw new SyntaxError("[destr] Invalid JSON");return t}try{if(yS.test(t)||wS.test(t)){if(e.strict)throw new Error("[destr] Possible prototype pollution");return JSON.parse(t,bS)}return JSON.parse(t)}catch(s){if(e.strict)throw s;return t}}const TS=/#/g,CS=/&/g,IS=/\//g,SS=/=/g,wd=/\+/g,AS=/%5e/gi,kS=/%60/gi,RS=/%7c/gi,PS=/%20/gi;function OS(t){return encodeURI(""+t).replace(RS,"|")}function Qu(t){return OS(typeof t=="string"?t:JSON.stringify(t)).replace(wd,"%2B").replace(PS,"+").replace(TS,"%23").replace(CS,"%26").replace(kS,"`").replace(AS,"^").replace(IS,"%2F")}function su(t){return Qu(t).replace(SS,"%3D")}function ol(t=""){try{return decodeURIComponent(""+t)}catch{return""+t}}function NS(t){return ol(t.replace(wd," "))}function xS(t){return ol(t.replace(wd," "))}function DS(t=""){const e={};t[0]==="?"&&(t=t.slice(1));for(const n of t.split("&")){const s=n.match(/([^=]+)=?(.*)/)||[];if(s.length<2)continue;const i=NS(s[1]);if(i==="__proto__"||i==="constructor")continue;const r=xS(s[2]||"");e[i]===void 0?e[i]=r:Array.isArray(e[i])?e[i].push(r):e[i]=[e[i],r]}return e}function LS(t,e){return(typeof e=="number"||typeof e=="boolean")&&(e=String(e)),e?Array.isArray(e)?e.map(n=>`${su(t)}=${Qu(n)}`).join("&"):`${su(t)}=${Qu(e)}`:su(t)}function MS(t){return Object.keys(t).filter(e=>t[e]!==void 0).map(e=>LS(e,t[e])).filter(Boolean).join("&")}const FS=/^[\s\w\0+.-]{2,}:([/\\]{1,2})/,US=/^[\s\w\0+.-]{2,}:([/\\]{2})?/,$S=/^([/\\]\s*){2,}[^/\\]/,HS=/^[\s\0]*(blob|data|javascript|vbscript):$/i,BS=/\/$|\/\?|\/#/,jS=/^\.?\//;function Ti(t,e={}){return typeof e=="boolean"&&(e={acceptRelative:e}),e.strict?FS.test(t):US.test(t)||(e.acceptRelative?$S.test(t):!1)}function VS(t){return!!t&&HS.test(t)}function Zu(t="",e){return e?BS.test(t):t.endsWith("/")}function vd(t="",e){if(!e)return(Zu(t)?t.slice(0,-1):t)||"/";if(!Zu(t,!0))return t||"/";let n=t,s="";const i=t.indexOf("#");i>=0&&(n=t.slice(0,i),s=t.slice(i));const[r,...o]=n.split("?");return((r.endsWith("/")?r.slice(0,-1):r)||"/")+(o.length>0?`?${o.join("?")}`:"")+s}function eh(t="",e){if(!e)return t.endsWith("/")?t:t+"/";if(Zu(t,!0))return t||"/";let n=t,s="";const i=t.indexOf("#");if(i>=0&&(n=t.slice(0,i),s=t.slice(i),!n))return s;const[r,...o]=n.split("?");return r+"/"+(o.length>0?`?${o.join("?")}`:"")+s}function WS(t=""){return t.startsWith("/")}function hg(t=""){return WS(t)?t:"/"+t}function KS(t,e){if(Rw(e)||Ti(t))return t;const n=vd(e);return t.startsWith(n)?t:bd(n,t)}function dg(t,e){if(Rw(e))return t;const n=vd(e);if(!t.startsWith(n))return t;const s=t.slice(n.length);return s[0]==="/"?s:"/"+s}function kw(t,e){const n=GS(t),s={...DS(n.search),...e};return n.search=MS(s),YS(n)}function Rw(t){return!t||t==="/"}function qS(t){return t&&t!=="/"}function bd(t,...e){let n=t||"";for(const s of e.filter(i=>qS(i)))if(n){const i=s.replace(jS,"");n=eh(n)+i}else n=s;return n}function Pw(...t){var o,l,c,u;const e=/\/(?!\/)/,n=t.filter(Boolean),s=[];let i=0;for(const h of n)if(!(!h||h==="/")){for(const[f,g]of h.split(e).entries())if(!(!g||g===".")){if(g===".."){if(s.length===1&&Ti(s[0]))continue;s.pop(),i--;continue}if(f===1&&((o=s[s.length-1])!=null&&o.endsWith(":/"))){s[s.length-1]+="/"+g;continue}s.push(g),i++}}let r=s.join("/");return i>=0?(l=n[0])!=null&&l.startsWith("/")&&!r.startsWith("/")?r="/"+r:(c=n[0])!=null&&c.startsWith("./")&&!r.startsWith("./")&&(r="./"+r):r="../".repeat(-1*i)+r,(u=n[n.length-1])!=null&&u.endsWith("/")&&!r.endsWith("/")&&(r+="/"),r}function zS(t,e,n={}){return n.trailingSlash||(t=eh(t),e=eh(e)),n.leadingSlash||(t=hg(t),e=hg(e)),n.encoding||(t=ol(t),e=ol(e)),t===e}const Ow=Symbol.for("ufo:protocolRelative");function GS(t="",e){const n=t.match(/^[\s\0]*(blob:|data:|javascript:|vbscript:)(.*)/i);if(n){const[,f,g=""]=n;return{protocol:f.toLowerCase(),pathname:g,href:f+g,auth:"",host:"",search:"",hash:""}}if(!Ti(t,{acceptRelative:!0}))return fg(t);const[,s="",i,r=""]=t.replace(/\\/g,"/").match(/^[\s\0]*([\w+.-]{2,}:)?\/\/([^/@]+@)?(.*)/)||[];let[,o="",l=""]=r.match(/([^#/?]*)(.*)?/)||[];s==="file:"&&(l=l.replace(/\/(?=[A-Za-z]:)/,""));const{pathname:c,search:u,hash:h}=fg(l);return{protocol:s.toLowerCase(),auth:i?i.slice(0,Math.max(0,i.length-1)):"",host:o,pathname:c,search:u,hash:h,[Ow]:!s}}function fg(t=""){const[e="",n="",s=""]=(t.match(/([^#?]*)(\?[^#]*)?(#.*)?/)||[]).splice(1);return{pathname:e,search:n,hash:s}}function YS(t){const e=t.pathname||"",n=t.search?(t.search.startsWith("?")?"":"?")+t.search:"",s=t.hash||"",i=t.auth?t.auth+"@":"",r=t.host||"";return(t.protocol||t[Ow]?(t.protocol||"")+"//":"")+i+r+e+n+s}class XS extends Error{constructor(e,n){super(e,n),this.name="FetchError",n!=null&&n.cause&&!this.cause&&(this.cause=n.cause)}}function JS(t){var c,u,h,f,g;const e=((c=t.error)==null?void 0:c.message)||((u=t.error)==null?void 0:u.toString())||"",n=((h=t.request)==null?void 0:h.method)||((f=t.options)==null?void 0:f.method)||"GET",s=((g=t.request)==null?void 0:g.url)||String(t.request)||"/",i=`[${n}] ${JSON.stringify(s)}`,r=t.response?`${t.response.status} ${t.response.statusText}`:"<no response>",o=`${i}: ${r}${e?` ${e}`:""}`,l=new XS(o,t.error?{cause:t.error}:void 0);for(const m of["request","options","response"])Object.defineProperty(l,m,{get(){return t[m]}});for(const[m,I]of[["data","_data"],["status","status"],["statusCode","status"],["statusText","statusText"],["statusMessage","statusText"]])Object.defineProperty(l,m,{get(){return t.response&&t.response[I]}});return l}const QS=new Set(Object.freeze(["PATCH","POST","PUT","DELETE"]));function pg(t="GET"){return QS.has(t.toUpperCase())}function ZS(t){if(t===void 0)return!1;const e=typeof t;return e==="string"||e==="number"||e==="boolean"||e===null?!0:e!=="object"?!1:Array.isArray(t)?!0:t.buffer?!1:t.constructor&&t.constructor.name==="Object"||typeof t.toJSON=="function"}const eA=new Set(["image/svg","application/xml","application/xhtml","application/html"]),tA=/^application\/(?:[\w!#$%&*.^`~-]*\+)?json(;.+)?$/i;function nA(t=""){if(!t)return"json";const e=t.split(";").shift()||"";return tA.test(e)?"json":eA.has(e)||e.startsWith("text/")?"text":"blob"}function sA(t,e,n,s){const i=iA((e==null?void 0:e.headers)??(t==null?void 0:t.headers),n==null?void 0:n.headers,s);let r;return(n!=null&&n.query||n!=null&&n.params||e!=null&&e.params||e!=null&&e.query)&&(r={...n==null?void 0:n.params,...n==null?void 0:n.query,...e==null?void 0:e.params,...e==null?void 0:e.query}),{...n,...e,query:r,params:r,headers:i}}function iA(t,e,n){if(!e)return new n(t);const s=new n(e);if(t)for(const[i,r]of Symbol.iterator in t||Array.isArray(t)?t:new n(t))s.set(i,r);return s}async function Sa(t,e){if(e)if(Array.isArray(e))for(const n of e)await n(t);else await e(t)}const rA=new Set([408,409,425,429,500,502,503,504]),oA=new Set([101,204,205,304]);function Nw(t={}){const{fetch:e=globalThis.fetch,Headers:n=globalThis.Headers,AbortController:s=globalThis.AbortController}=t;async function i(l){const c=l.error&&l.error.name==="AbortError"&&!l.options.timeout||!1;if(l.options.retry!==!1&&!c){let h;typeof l.options.retry=="number"?h=l.options.retry:h=pg(l.options.method)?0:1;const f=l.response&&l.response.status||500;if(h>0&&(Array.isArray(l.options.retryStatusCodes)?l.options.retryStatusCodes.includes(f):rA.has(f))){const g=typeof l.options.retryDelay=="function"?l.options.retryDelay(l):l.options.retryDelay||0;return g>0&&await new Promise(m=>setTimeout(m,g)),r(l.request,{...l.options,retry:h-1})}}const u=JS(l);throw Error.captureStackTrace&&Error.captureStackTrace(u,r),u}const r=async function(c,u={}){var m;const h={request:c,options:sA(c,u,t.defaults,n),response:void 0,error:void 0};h.options.method=(m=h.options.method)==null?void 0:m.toUpperCase(),h.options.onRequest&&await Sa(h,h.options.onRequest),typeof h.request=="string"&&(h.options.baseURL&&(h.request=KS(h.request,h.options.baseURL)),h.options.query&&(h.request=kw(h.request,h.options.query))),h.options.body&&pg(h.options.method)&&(ZS(h.options.body)?(h.options.body=typeof h.options.body=="string"?h.options.body:JSON.stringify(h.options.body),h.options.headers=new n(h.options.headers||{}),h.options.headers.has("content-type")||h.options.headers.set("content-type","application/json"),h.options.headers.has("accept")||h.options.headers.set("accept","application/json")):("pipeTo"in h.options.body&&typeof h.options.body.pipeTo=="function"||typeof h.options.body.pipe=="function")&&("duplex"in h.options||(h.options.duplex="half")));let f;if(!h.options.signal&&h.options.timeout){const I=new s;f=setTimeout(()=>{const P=new Error("[TimeoutError]: The operation was aborted due to timeout");P.name="TimeoutError",P.code=23,I.abort(P)},h.options.timeout),h.options.signal=I.signal}try{h.response=await e(h.request,h.options)}catch(I){return h.error=I,h.options.onRequestError&&await Sa(h,h.options.onRequestError),await i(h)}finally{f&&clearTimeout(f)}if(h.response.body&&!oA.has(h.response.status)&&h.options.method!=="HEAD"){const I=(h.options.parseResponse?"json":h.options.responseType)||nA(h.response.headers.get("content-type")||"");switch(I){case"json":{const P=await h.response.text(),D=h.options.parseResponse||rl;h.response._data=D(P);break}case"stream":{h.response._data=h.response.body;break}default:h.response._data=await h.response[I]()}}return h.options.onResponse&&await Sa(h,h.options.onResponse),!h.options.ignoreResponseError&&h.response.status>=400&&h.response.status<600?(h.options.onResponseError&&await Sa(h,h.options.onResponseError),await i(h)):h.response},o=async function(c,u){return(await r(c,u))._data};return o.raw=r,o.native=(...l)=>e(...l),o.create=(l={},c={})=>Nw({...t,...c,defaults:{...t.defaults,...c.defaults,...l}}),o}const al=function(){if(typeof globalThis<"u")return globalThis;if(typeof self<"u")return self;if(typeof window<"u")return window;if(typeof global<"u")return global;throw new Error("unable to locate global object")}(),aA=al.fetch?(...t)=>al.fetch(...t):()=>Promise.reject(new Error("[ofetch] global.fetch is not supported!")),lA=al.Headers,cA=al.AbortController,uA=Nw({fetch:aA,Headers:lA,AbortController:cA}),hA=uA,dA=()=>{var t;return((t=window==null?void 0:window.__NUXT__)==null?void 0:t.config)||{}},ll=dA().app,fA=()=>ll.baseURL,pA=()=>ll.buildAssetsDir,Ed=(...t)=>Pw(xw(),pA(),...t),xw=(...t)=>{const e=ll.cdnURL||ll.baseURL;return t.length?Pw(e,...t):e};globalThis.__buildAssetsURL=Ed,globalThis.__publicAssetsURL=xw;globalThis.$fetch||(globalThis.$fetch=hA.create({baseURL:fA()}));function th(t,e={},n){for(const s in t){const i=t[s],r=n?`${n}:${s}`:s;typeof i=="object"&&i!==null?th(i,e,r):typeof i=="function"&&(e[r]=i)}return e}const gA={run:t=>t()},mA=()=>gA,Dw=typeof console.createTask<"u"?console.createTask:mA;function _A(t,e){const n=e.shift(),s=Dw(n);return t.reduce((i,r)=>i.then(()=>s.run(()=>r(...e))),Promise.resolve())}function yA(t,e){const n=e.shift(),s=Dw(n);return Promise.all(t.map(i=>s.run(()=>i(...e))))}function iu(t,e){for(const n of[...t])n(e)}class wA{constructor(){this._hooks={},this._before=void 0,this._after=void 0,this._deprecatedMessages=void 0,this._deprecatedHooks={},this.hook=this.hook.bind(this),this.callHook=this.callHook.bind(this),this.callHookWith=this.callHookWith.bind(this)}hook(e,n,s={}){if(!e||typeof n!="function")return()=>{};const i=e;let r;for(;this._deprecatedHooks[e];)r=this._deprecatedHooks[e],e=r.to;if(r&&!s.allowDeprecated){let o=r.message;o||(o=`${i} hook has been deprecated`+(r.to?`, please use ${r.to}`:"")),this._deprecatedMessages||(this._deprecatedMessages=new Set),this._deprecatedMessages.has(o)||(console.warn(o),this._deprecatedMessages.add(o))}if(!n.name)try{Object.defineProperty(n,"name",{get:()=>"_"+e.replace(/\W+/g,"_")+"_hook_cb",configurable:!0})}catch{}return this._hooks[e]=this._hooks[e]||[],this._hooks[e].push(n),()=>{n&&(this.removeHook(e,n),n=void 0)}}hookOnce(e,n){let s,i=(...r)=>(typeof s=="function"&&s(),s=void 0,i=void 0,n(...r));return s=this.hook(e,i),s}removeHook(e,n){if(this._hooks[e]){const s=this._hooks[e].indexOf(n);s!==-1&&this._hooks[e].splice(s,1),this._hooks[e].length===0&&delete this._hooks[e]}}deprecateHook(e,n){this._deprecatedHooks[e]=typeof n=="string"?{to:n}:n;const s=this._hooks[e]||[];delete this._hooks[e];for(const i of s)this.hook(e,i)}deprecateHooks(e){Object.assign(this._deprecatedHooks,e);for(const n in e)this.deprecateHook(n,e[n])}addHooks(e){const n=th(e),s=Object.keys(n).map(i=>this.hook(i,n[i]));return()=>{for(const i of s.splice(0,s.length))i()}}removeHooks(e){const n=th(e);for(const s in n)this.removeHook(s,n[s])}removeAllHooks(){for(const e in this._hooks)delete this._hooks[e]}callHook(e,...n){return n.unshift(e),this.callHookWith(_A,e,...n)}callHookParallel(e,...n){return n.unshift(e),this.callHookWith(yA,e,...n)}callHookWith(e,n,...s){const i=this._before||this._after?{name:n,args:s,context:{}}:void 0;this._before&&iu(this._before,i);const r=e(n in this._hooks?[...this._hooks[n]]:[],s);return r instanceof Promise?r.finally(()=>{this._after&&i&&iu(this._after,i)}):(this._after&&i&&iu(this._after,i),r)}beforeEach(e){return this._before=this._before||[],this._before.push(e),()=>{if(this._before!==void 0){const n=this._before.indexOf(e);n!==-1&&this._before.splice(n,1)}}}afterEach(e){return this._after=this._after||[],this._after.push(e),()=>{if(this._after!==void 0){const n=this._after.indexOf(e);n!==-1&&this._after.splice(n,1)}}}}function Lw(){return new wA}function vA(t={}){let e,n=!1;const s=o=>{if(e&&e!==o)throw new Error("Context conflict")};let i;if(t.asyncContext){const o=t.AsyncLocalStorage||globalThis.AsyncLocalStorage;o?i=new o:console.warn("[unctx] `AsyncLocalStorage` is not provided.")}const r=()=>{if(i&&e===void 0){const o=i.getStore();if(o!==void 0)return o}return e};return{use:()=>{const o=r();if(o===void 0)throw new Error("Context is not available");return o},tryUse:()=>r(),set:(o,l)=>{l||s(o),e=o,n=!0},unset:()=>{e=void 0,n=!1},call:(o,l)=>{s(o),e=o;try{return i?i.run(o,l):l()}finally{n||(e=void 0)}},async callAsync(o,l){e=o;const c=()=>{e=o},u=()=>e===o?c:void 0;nh.add(u);try{const h=i?i.run(o,l):l();return n||(e=void 0),await h}finally{nh.delete(u)}}}}function bA(t={}){const e={};return{get(n,s={}){return e[n]||(e[n]=vA({...t,...s})),e[n],e[n]}}}const cl=typeof globalThis<"u"?globalThis:typeof self<"u"?self:typeof global<"u"?global:typeof window<"u"?window:{},gg="__unctx__",EA=cl[gg]||(cl[gg]=bA()),TA=(t,e={})=>EA.get(t,e),mg="__unctx_async_handlers__",nh=cl[mg]||(cl[mg]=new Set);function Qi(t){const e=[];for(const i of nh){const r=i();r&&e.push(r)}const n=()=>{for(const i of e)i()};let s=t();return s&&typeof s=="object"&&"catch"in s&&(s=s.catch(i=>{throw n(),i})),[s,n]}const CA=!1,sh=!1,IA=!1,dH={componentName:"NuxtLink",prefetch:!0,prefetchOn:{visibility:!0}},SA=null,AA="#__nuxt",Mw="nuxt-app",_g=36e5,kA="vite:preloadError";function Fw(t=Mw){return TA(t,{asyncContext:!1})}const RA="__nuxt_plugin";function PA(t){var i;let e=0;const n={_id:t.id||Mw||"nuxt-app",_scope:sy(),provide:void 0,globalName:"nuxt",versions:{get nuxt(){return"3.13.2"},get vue(){return n.vueApp.version}},payload:Gn({...((i=t.ssrContext)==null?void 0:i.payload)||{},data:Gn({}),state:In({}),once:new Set,_errors:Gn({})}),static:{data:{}},runWithContext(r){return n._scope.active&&!iy()?n._scope.run(()=>yg(n,r)):yg(n,r)},isHydrating:!0,deferHydration(){if(!n.isHydrating)return()=>{};e++;let r=!1;return()=>{if(!r&&(r=!0,e--,e===0))return n.isHydrating=!1,n.callHook("app:suspense:resolve")}},_asyncDataPromises:{},_asyncData:Gn({}),_payloadRevivers:{},...t};{const r=window.__NUXT__;if(r)for(const o in r)switch(o){case"data":case"state":case"_errors":Object.assign(n.payload[o],r[o]);break;default:n.payload[o]=r[o]}}n.hooks=Lw(),n.hook=n.hooks.hook,n.callHook=n.hooks.callHook,n.provide=(r,o)=>{const l="$"+r;Aa(n,l,o),Aa(n.vueApp.config.globalProperties,l,o)},Aa(n.vueApp,"$nuxt",n),Aa(n.vueApp.config.globalProperties,"$nuxt",n);{window.addEventListener(kA,o=>{n.callHook("app:chunkError",{error:o.payload}),(n.isHydrating||o.payload.message.includes("Unable to preload CSS"))&&o.preventDefault()}),window.useNuxtApp=window.useNuxtApp||Je;const r=n.hook("app:error",(...o)=>{console.error("[nuxt] error caught during app initialization",...o)});n.hook("app:mounted",r)}const s=n.payload.config;return n.provide("config",s),n}function OA(t,e){e.hooks&&t.hooks.addHooks(e.hooks)}async function NA(t,e){if(typeof e=="function"){const{provide:n}=await t.runWithContext(()=>e(t))||{};if(n&&typeof n=="object")for(const s in n)t.provide(s,n[s])}}async function xA(t,e){const n=[],s=[],i=[],r=[];let o=0;async function l(c){var h;const u=((h=c.dependsOn)==null?void 0:h.filter(f=>e.some(g=>g._name===f)&&!n.includes(f)))??[];if(u.length>0)s.push([new Set(u),c]);else{const f=NA(t,c).then(async()=>{c._name&&(n.push(c._name),await Promise.all(s.map(async([g,m])=>{g.has(c._name)&&(g.delete(c._name),g.size===0&&(o++,await l(m)))})))});c.parallel?i.push(f.catch(g=>r.push(g))):await f}}for(const c of e)OA(t,c);for(const c of e)await l(c);if(await Promise.all(i),o)for(let c=0;c<o;c++)await Promise.all(i);if(r.length)throw r[0]}function Rt(t){if(typeof t=="function")return t;const e=t._name||t.name;return delete t.name,Object.assign(t.setup||(()=>{}),t,{[RA]:!0,_name:e})}const DA=Rt;function yg(t,e,n){const s=()=>e();return Fw(t._id).set(t),t.vueApp.runWithContext(s)}function LA(t){var n;let e;return zy()&&(e=(n=Ei())==null?void 0:n.appContext.app.$nuxt),e=e||Fw(t).tryUse(),e||null}function Je(t){const e=LA(t);if(!e)throw new Error("[nuxt] instance unavailable");return e}function Mo(t){return Je().$config}function Aa(t,e,n){Object.defineProperty(t,e,{get:()=>n})}function MA(t,e){return{ctx:{table:t},matchAll:n=>$w(n,t)}}function Uw(t){const e={};for(const n in t)e[n]=n==="dynamic"?new Map(Object.entries(t[n]).map(([s,i])=>[s,Uw(i)])):new Map(Object.entries(t[n]));return e}function FA(t){return MA(Uw(t))}function $w(t,e,n){t.endsWith("/")&&(t=t.slice(0,-1)||"/");const s=[];for(const[r,o]of wg(e.wildcard))(t===r||t.startsWith(r+"/"))&&s.push(o);for(const[r,o]of wg(e.dynamic))if(t.startsWith(r+"/")){const l="/"+t.slice(r.length).split("/").splice(2).join("/");s.push(...$w(l,o))}const i=e.static.get(t);return i&&s.push(i),s.filter(Boolean)}function wg(t){return[...t.entries()].sort((e,n)=>e[0].length-n[0].length)}function ru(t){if(t===null||typeof t!="object")return!1;const e=Object.getPrototypeOf(t);return e!==null&&e!==Object.prototype&&Object.getPrototypeOf(e)!==null||Symbol.iterator in t?!1:Symbol.toStringTag in t?Object.prototype.toString.call(t)==="[object Module]":!0}function ih(t,e,n=".",s){if(!ru(e))return ih(t,{},n,s);const i=Object.assign({},e);for(const r in t){if(r==="__proto__"||r==="constructor")continue;const o=t[r];o!=null&&(s&&s(i,r,o,n)||(Array.isArray(o)&&Array.isArray(i[r])?i[r]=[...o,...i[r]]:ru(o)&&ru(i[r])?i[r]=ih(o,i[r],(n?`${n}.`:"")+r.toString(),s):i[r]=o))}return i}function UA(t){return(...e)=>e.reduce((n,s)=>ih(n,s,"",t),{})}const Hw=UA();function $A(t,e){try{return e in t}catch{return!1}}var HA=Object.defineProperty,BA=(t,e,n)=>e in t?HA(t,e,{enumerable:!0,configurable:!0,writable:!0,value:n}):t[e]=n,Zs=(t,e,n)=>(BA(t,typeof e!="symbol"?e+"":e,n),n);class rh extends Error{constructor(e,n={}){super(e,n),Zs(this,"statusCode",500),Zs(this,"fatal",!1),Zs(this,"unhandled",!1),Zs(this,"statusMessage"),Zs(this,"data"),Zs(this,"cause"),n.cause&&!this.cause&&(this.cause=n.cause)}toJSON(){const e={message:this.message,statusCode:ah(this.statusCode,500)};return this.statusMessage&&(e.statusMessage=Bw(this.statusMessage)),this.data!==void 0&&(e.data=this.data),e}}Zs(rh,"__h3_error__",!0);function oh(t){if(typeof t=="string")return new rh(t);if(jA(t))return t;const e=new rh(t.message??t.statusMessage??"",{cause:t.cause||t});if($A(t,"stack"))try{Object.defineProperty(e,"stack",{get(){return t.stack}})}catch{try{e.stack=t.stack}catch{}}if(t.data&&(e.data=t.data),t.statusCode?e.statusCode=ah(t.statusCode,e.statusCode):t.status&&(e.statusCode=ah(t.status,e.statusCode)),t.statusMessage?e.statusMessage=t.statusMessage:t.statusText&&(e.statusMessage=t.statusText),e.statusMessage){const n=e.statusMessage;Bw(e.statusMessage)!==n&&console.warn("[h3] Please prefer using `message` for longer error messages instead of `statusMessage`. In the future, `statusMessage` will be sanitized by default.")}return t.fatal!==void 0&&(e.fatal=t.fatal),t.unhandled!==void 0&&(e.unhandled=t.unhandled),e}function jA(t){var e;return((e=t==null?void 0:t.constructor)==null?void 0:e.__h3_error__)===!0}const VA=/[^\u0009\u0020-\u007E]/g;function Bw(t=""){return t.replace(VA,"")}function ah(t,e=200){return!t||(typeof t=="string"&&(t=Number.parseInt(t,10)),t<100||t>999)?e:t}const jw=Symbol("layout-meta"),Fo=Symbol("route"),hn=()=>{var t;return(t=Je())==null?void 0:t.$router},Td=()=>zy()?ht(Fo,Je()._route):Je()._route;function fH(t){return t}const WA=()=>{try{if(Je()._processingMiddleware)return!0}catch{return!1}return!1},Vw=(t,e)=>{t||(t="/");const n=typeof t=="string"?t:"path"in t?KA(t):hn().resolve(t).href;if(e!=null&&e.open){const{target:c="_blank",windowFeatures:u={}}=e.open,h=Object.entries(u).filter(([f,g])=>g!==void 0).map(([f,g])=>`${f.toLowerCase()}=${g}`).join(", ");return open(n,c,h),Promise.resolve()}const s=Ti(n,{acceptRelative:!0}),i=(e==null?void 0:e.external)||s;if(i){if(!(e!=null&&e.external))throw new Error("Navigating to an external URL is not allowed by default. Use `navigateTo(url, { external: true })`.");const{protocol:c}=new URL(n,window.location.href);if(c&&VS(c))throw new Error(`Cannot navigate to a URL with '${c}' protocol.`)}const r=WA();if(!i&&r)return t;const o=hn(),l=Je();return i?(l._scope.stop(),e!=null&&e.replace?location.replace(n):location.href=n,r?l.isHydrating?new Promise(()=>{}):!1:Promise.resolve()):e!=null&&e.replace?o.replace(t):o.push(t)};function KA(t){return kw(t.path||"",t.query||{})+(t.hash||"")}const Ww="__nuxt_error",nc=()=>CC(Je().payload,"error"),ji=t=>{const e=sc(t);try{const n=Je(),s=nc();n.hooks.callHook("app:error",e),s.value=s.value||e}catch{throw e}return e},qA=async(t={})=>{const e=Je(),n=nc();e.callHook("app:error:cleared",t),t.redirect&&await hn().replace(t.redirect),n.value=SA},zA=t=>!!t&&typeof t=="object"&&Ww in t,sc=t=>{const e=oh(t);return Object.defineProperty(e,Ww,{value:!0,configurable:!1,writable:!1}),e};var vg={};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Kw={NODE_CLIENT:!1,NODE_ADMIN:!1,SDK_VERSION:"${JSCORE_VERSION}"};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Y=function(t,e){if(!t)throw wr(e)},wr=function(t){return new Error("Firebase Database ("+Kw.SDK_VERSION+") INTERNAL ASSERT FAILED: "+t)};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const qw=function(t){const e=[];let n=0;for(let s=0;s<t.length;s++){let i=t.charCodeAt(s);i<128?e[n++]=i:i<2048?(e[n++]=i>>6|192,e[n++]=i&63|128):(i&64512)===55296&&s+1<t.length&&(t.charCodeAt(s+1)&64512)===56320?(i=65536+((i&1023)<<10)+(t.charCodeAt(++s)&1023),e[n++]=i>>18|240,e[n++]=i>>12&63|128,e[n++]=i>>6&63|128,e[n++]=i&63|128):(e[n++]=i>>12|224,e[n++]=i>>6&63|128,e[n++]=i&63|128)}return e},GA=function(t){const e=[];let n=0,s=0;for(;n<t.length;){const i=t[n++];if(i<128)e[s++]=String.fromCharCode(i);else if(i>191&&i<224){const r=t[n++];e[s++]=String.fromCharCode((i&31)<<6|r&63)}else if(i>239&&i<365){const r=t[n++],o=t[n++],l=t[n++],c=((i&7)<<18|(r&63)<<12|(o&63)<<6|l&63)-65536;e[s++]=String.fromCharCode(55296+(c>>10)),e[s++]=String.fromCharCode(56320+(c&1023))}else{const r=t[n++],o=t[n++];e[s++]=String.fromCharCode((i&15)<<12|(r&63)<<6|o&63)}}return e.join("")},ic={byteToCharMap_:null,charToByteMap_:null,byteToCharMapWebSafe_:null,charToByteMapWebSafe_:null,ENCODED_VALS_BASE:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",get ENCODED_VALS(){return this.ENCODED_VALS_BASE+"+/="},get ENCODED_VALS_WEBSAFE(){return this.ENCODED_VALS_BASE+"-_."},HAS_NATIVE_SUPPORT:typeof atob=="function",encodeByteArray(t,e){if(!Array.isArray(t))throw Error("encodeByteArray takes an array as a parameter");this.init_();const n=e?this.byteToCharMapWebSafe_:this.byteToCharMap_,s=[];for(let i=0;i<t.length;i+=3){const r=t[i],o=i+1<t.length,l=o?t[i+1]:0,c=i+2<t.length,u=c?t[i+2]:0,h=r>>2,f=(r&3)<<4|l>>4;let g=(l&15)<<2|u>>6,m=u&63;c||(m=64,o||(g=64)),s.push(n[h],n[f],n[g],n[m])}return s.join("")},encodeString(t,e){return this.HAS_NATIVE_SUPPORT&&!e?btoa(t):this.encodeByteArray(qw(t),e)},decodeString(t,e){return this.HAS_NATIVE_SUPPORT&&!e?atob(t):GA(this.decodeStringToByteArray(t,e))},decodeStringToByteArray(t,e){this.init_();const n=e?this.charToByteMapWebSafe_:this.charToByteMap_,s=[];for(let i=0;i<t.length;){const r=n[t.charAt(i++)],l=i<t.length?n[t.charAt(i)]:0;++i;const u=i<t.length?n[t.charAt(i)]:64;++i;const f=i<t.length?n[t.charAt(i)]:64;if(++i,r==null||l==null||u==null||f==null)throw new YA;const g=r<<2|l>>4;if(s.push(g),u!==64){const m=l<<4&240|u>>2;if(s.push(m),f!==64){const I=u<<6&192|f;s.push(I)}}}return s},init_(){if(!this.byteToCharMap_){this.byteToCharMap_={},this.charToByteMap_={},this.byteToCharMapWebSafe_={},this.charToByteMapWebSafe_={};for(let t=0;t<this.ENCODED_VALS.length;t++)this.byteToCharMap_[t]=this.ENCODED_VALS.charAt(t),this.charToByteMap_[this.byteToCharMap_[t]]=t,this.byteToCharMapWebSafe_[t]=this.ENCODED_VALS_WEBSAFE.charAt(t),this.charToByteMapWebSafe_[this.byteToCharMapWebSafe_[t]]=t,t>=this.ENCODED_VALS_BASE.length&&(this.charToByteMap_[this.ENCODED_VALS_WEBSAFE.charAt(t)]=t,this.charToByteMapWebSafe_[this.ENCODED_VALS.charAt(t)]=t)}}};class YA extends Error{constructor(){super(...arguments),this.name="DecodeBase64StringError"}}const zw=function(t){const e=qw(t);return ic.encodeByteArray(e,!0)},Gw=function(t){return zw(t).replace(/\./g,"")},ul=function(t){try{return ic.decodeString(t,!0)}catch(e){console.error("base64Decode failed: ",e)}return null};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function XA(t){return Yw(void 0,t)}function Yw(t,e){if(!(e instanceof Object))return e;switch(e.constructor){case Date:const n=e;return new Date(n.getTime());case Object:t===void 0&&(t={});break;case Array:t=[];break;default:return e}for(const n in e)!e.hasOwnProperty(n)||!JA(n)||(t[n]=Yw(t[n],e[n]));return t}function JA(t){return t!=="__proto__"}/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function QA(){if(typeof self<"u")return self;if(typeof window<"u")return window;if(typeof global<"u")return global;throw new Error("Unable to locate global object.")}/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ZA=()=>QA().__FIREBASE_DEFAULTS__,ek=()=>{if(typeof process>"u"||typeof vg>"u")return;const t=vg.__FIREBASE_DEFAULTS__;if(t)return JSON.parse(t)},tk=()=>{if(typeof document>"u")return;let t;try{t=document.cookie.match(/__FIREBASE_DEFAULTS__=([^;]+)/)}catch{return}const e=t&&ul(t[1]);return e&&JSON.parse(e)},Cd=()=>{try{return ZA()||ek()||tk()}catch(t){console.info(`Unable to get __FIREBASE_DEFAULTS__ due to: ${t}`);return}},nk=t=>{var e,n;return(n=(e=Cd())===null||e===void 0?void 0:e.emulatorHosts)===null||n===void 0?void 0:n[t]},Xw=()=>{var t;return(t=Cd())===null||t===void 0?void 0:t.config},Jw=t=>{var e;return(e=Cd())===null||e===void 0?void 0:e[`_${t}`]};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class vo{constructor(){this.reject=()=>{},this.resolve=()=>{},this.promise=new Promise((e,n)=>{this.resolve=e,this.reject=n})}wrapCallback(e){return(n,s)=>{n?this.reject(n):this.resolve(s),typeof e=="function"&&(this.promise.catch(()=>{}),e.length===1?e(n):e(n,s))}}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Lt(){return typeof navigator<"u"&&typeof navigator.userAgent=="string"?navigator.userAgent:""}function Id(){return typeof window<"u"&&!!(window.cordova||window.phonegap||window.PhoneGap)&&/ios|iphone|ipod|ipad|android|blackberry|iemobile/i.test(Lt())}function sk(){return typeof navigator<"u"&&navigator.userAgent==="Cloudflare-Workers"}function ik(){const t=typeof chrome=="object"?chrome.runtime:typeof browser=="object"?browser.runtime:void 0;return typeof t=="object"&&t.id!==void 0}function Qw(){return typeof navigator=="object"&&navigator.product==="ReactNative"}function rk(){const t=Lt();return t.indexOf("MSIE ")>=0||t.indexOf("Trident/")>=0}function Zw(){return Kw.NODE_ADMIN===!0}function Sd(){try{return typeof indexedDB=="object"}catch{return!1}}function ev(){return new Promise((t,e)=>{try{let n=!0;const s="validate-browser-context-for-indexeddb-analytics-module",i=self.indexedDB.open(s);i.onsuccess=()=>{i.result.close(),n||self.indexedDB.deleteDatabase(s),t(!0)},i.onupgradeneeded=()=>{n=!1},i.onerror=()=>{var r;e(((r=i.error)===null||r===void 0?void 0:r.message)||"")}}catch(n){e(n)}})}function ok(){return!(typeof navigator>"u"||!navigator.cookieEnabled)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ak="FirebaseError";class Sn extends Error{constructor(e,n,s){super(n),this.code=e,this.customData=s,this.name=ak,Object.setPrototypeOf(this,Sn.prototype),Error.captureStackTrace&&Error.captureStackTrace(this,Bs.prototype.create)}}class Bs{constructor(e,n,s){this.service=e,this.serviceName=n,this.errors=s}create(e,...n){const s=n[0]||{},i=`${this.service}/${e}`,r=this.errors[e],o=r?lk(r,s):"Error",l=`${this.serviceName}: ${o} (${i}).`;return new Sn(i,l,s)}}function lk(t,e){return t.replace(ck,(n,s)=>{const i=e[s];return i!=null?String(i):`<${s}?>`})}const ck=/\{\$([^}]+)}/g;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function bo(t){return JSON.parse(t)}function gt(t){return JSON.stringify(t)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const tv=function(t){let e={},n={},s={},i="";try{const r=t.split(".");e=bo(ul(r[0])||""),n=bo(ul(r[1])||""),i=r[2],s=n.d||{},delete n.d}catch{}return{header:e,claims:n,data:s,signature:i}},uk=function(t){const e=tv(t),n=e.claims;return!!n&&typeof n=="object"&&n.hasOwnProperty("iat")},hk=function(t){const e=tv(t).claims;return typeof e=="object"&&e.admin===!0};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function us(t,e){return Object.prototype.hasOwnProperty.call(t,e)}function ur(t,e){if(Object.prototype.hasOwnProperty.call(t,e))return t[e]}function lh(t){for(const e in t)if(Object.prototype.hasOwnProperty.call(t,e))return!1;return!0}function hl(t,e,n){const s={};for(const i in t)Object.prototype.hasOwnProperty.call(t,i)&&(s[i]=e.call(n,t[i],i,t));return s}function dl(t,e){if(t===e)return!0;const n=Object.keys(t),s=Object.keys(e);for(const i of n){if(!s.includes(i))return!1;const r=t[i],o=e[i];if(bg(r)&&bg(o)){if(!dl(r,o))return!1}else if(r!==o)return!1}for(const i of s)if(!n.includes(i))return!1;return!0}function bg(t){return t!==null&&typeof t=="object"}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function vr(t){const e=[];for(const[n,s]of Object.entries(t))Array.isArray(s)?s.forEach(i=>{e.push(encodeURIComponent(n)+"="+encodeURIComponent(i))}):e.push(encodeURIComponent(n)+"="+encodeURIComponent(s));return e.length?"&"+e.join("&"):""}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class dk{constructor(){this.chain_=[],this.buf_=[],this.W_=[],this.pad_=[],this.inbuf_=0,this.total_=0,this.blockSize=512/8,this.pad_[0]=128;for(let e=1;e<this.blockSize;++e)this.pad_[e]=0;this.reset()}reset(){this.chain_[0]=1732584193,this.chain_[1]=4023233417,this.chain_[2]=2562383102,this.chain_[3]=271733878,this.chain_[4]=3285377520,this.inbuf_=0,this.total_=0}compress_(e,n){n||(n=0);const s=this.W_;if(typeof e=="string")for(let f=0;f<16;f++)s[f]=e.charCodeAt(n)<<24|e.charCodeAt(n+1)<<16|e.charCodeAt(n+2)<<8|e.charCodeAt(n+3),n+=4;else for(let f=0;f<16;f++)s[f]=e[n]<<24|e[n+1]<<16|e[n+2]<<8|e[n+3],n+=4;for(let f=16;f<80;f++){const g=s[f-3]^s[f-8]^s[f-14]^s[f-16];s[f]=(g<<1|g>>>31)&4294967295}let i=this.chain_[0],r=this.chain_[1],o=this.chain_[2],l=this.chain_[3],c=this.chain_[4],u,h;for(let f=0;f<80;f++){f<40?f<20?(u=l^r&(o^l),h=1518500249):(u=r^o^l,h=1859775393):f<60?(u=r&o|l&(r|o),h=2400959708):(u=r^o^l,h=3395469782);const g=(i<<5|i>>>27)+u+c+h+s[f]&4294967295;c=l,l=o,o=(r<<30|r>>>2)&4294967295,r=i,i=g}this.chain_[0]=this.chain_[0]+i&4294967295,this.chain_[1]=this.chain_[1]+r&4294967295,this.chain_[2]=this.chain_[2]+o&4294967295,this.chain_[3]=this.chain_[3]+l&4294967295,this.chain_[4]=this.chain_[4]+c&4294967295}update(e,n){if(e==null)return;n===void 0&&(n=e.length);const s=n-this.blockSize;let i=0;const r=this.buf_;let o=this.inbuf_;for(;i<n;){if(o===0)for(;i<=s;)this.compress_(e,i),i+=this.blockSize;if(typeof e=="string"){for(;i<n;)if(r[o]=e.charCodeAt(i),++o,++i,o===this.blockSize){this.compress_(r),o=0;break}}else for(;i<n;)if(r[o]=e[i],++o,++i,o===this.blockSize){this.compress_(r),o=0;break}}this.inbuf_=o,this.total_+=n}digest(){const e=[];let n=this.total_*8;this.inbuf_<56?this.update(this.pad_,56-this.inbuf_):this.update(this.pad_,this.blockSize-(this.inbuf_-56));for(let i=this.blockSize-1;i>=56;i--)this.buf_[i]=n&255,n/=256;this.compress_(this.buf_);let s=0;for(let i=0;i<5;i++)for(let r=24;r>=0;r-=8)e[s]=this.chain_[i]>>r&255,++s;return e}}function fk(t,e){const n=new pk(t,e);return n.subscribe.bind(n)}class pk{constructor(e,n){this.observers=[],this.unsubscribes=[],this.observerCount=0,this.task=Promise.resolve(),this.finalized=!1,this.onNoObservers=n,this.task.then(()=>{e(this)}).catch(s=>{this.error(s)})}next(e){this.forEachObserver(n=>{n.next(e)})}error(e){this.forEachObserver(n=>{n.error(e)}),this.close(e)}complete(){this.forEachObserver(e=>{e.complete()}),this.close()}subscribe(e,n,s){let i;if(e===void 0&&n===void 0&&s===void 0)throw new Error("Missing Observer.");gk(e,["next","error","complete"])?i=e:i={next:e,error:n,complete:s},i.next===void 0&&(i.next=ou),i.error===void 0&&(i.error=ou),i.complete===void 0&&(i.complete=ou);const r=this.unsubscribeOne.bind(this,this.observers.length);return this.finalized&&this.task.then(()=>{try{this.finalError?i.error(this.finalError):i.complete()}catch{}}),this.observers.push(i),r}unsubscribeOne(e){this.observers===void 0||this.observers[e]===void 0||(delete this.observers[e],this.observerCount-=1,this.observerCount===0&&this.onNoObservers!==void 0&&this.onNoObservers(this))}forEachObserver(e){if(!this.finalized)for(let n=0;n<this.observers.length;n++)this.sendOne(n,e)}sendOne(e,n){this.task.then(()=>{if(this.observers!==void 0&&this.observers[e]!==void 0)try{n(this.observers[e])}catch(s){typeof console<"u"&&console.error&&console.error(s)}})}close(e){this.finalized||(this.finalized=!0,e!==void 0&&(this.finalError=e),this.task.then(()=>{this.observers=void 0,this.onNoObservers=void 0}))}}function gk(t,e){if(typeof t!="object"||t===null)return!1;for(const n of e)if(n in t&&typeof t[n]=="function")return!0;return!1}function ou(){}function mk(t,e){return`${t} failed: ${e} argument `}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const _k=function(t){const e=[];let n=0;for(let s=0;s<t.length;s++){let i=t.charCodeAt(s);if(i>=55296&&i<=56319){const r=i-55296;s++,Y(s<t.length,"Surrogate pair missing trail surrogate.");const o=t.charCodeAt(s)-56320;i=65536+(r<<10)+o}i<128?e[n++]=i:i<2048?(e[n++]=i>>6|192,e[n++]=i&63|128):i<65536?(e[n++]=i>>12|224,e[n++]=i>>6&63|128,e[n++]=i&63|128):(e[n++]=i>>18|240,e[n++]=i>>12&63|128,e[n++]=i>>6&63|128,e[n++]=i&63|128)}return e},rc=function(t){let e=0;for(let n=0;n<t.length;n++){const s=t.charCodeAt(n);s<128?e++:s<2048?e+=2:s>=55296&&s<=56319?(e+=4,n++):e+=3}return e};/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Un(t){return t&&t._delegate?t._delegate:t}class Mt{constructor(e,n,s){this.name=e,this.instanceFactory=n,this.type=s,this.multipleInstances=!1,this.serviceProps={},this.instantiationMode="LAZY",this.onInstanceCreated=null}setInstantiationMode(e){return this.instantiationMode=e,this}setMultipleInstances(e){return this.multipleInstances=e,this}setServiceProps(e){return this.serviceProps=e,this}setInstanceCreatedCallback(e){return this.onInstanceCreated=e,this}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ei="[DEFAULT]";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class yk{constructor(e,n){this.name=e,this.container=n,this.component=null,this.instances=new Map,this.instancesDeferred=new Map,this.instancesOptions=new Map,this.onInitCallbacks=new Map}get(e){const n=this.normalizeInstanceIdentifier(e);if(!this.instancesDeferred.has(n)){const s=new vo;if(this.instancesDeferred.set(n,s),this.isInitialized(n)||this.shouldAutoInitialize())try{const i=this.getOrInitializeService({instanceIdentifier:n});i&&s.resolve(i)}catch{}}return this.instancesDeferred.get(n).promise}getImmediate(e){var n;const s=this.normalizeInstanceIdentifier(e==null?void 0:e.identifier),i=(n=e==null?void 0:e.optional)!==null&&n!==void 0?n:!1;if(this.isInitialized(s)||this.shouldAutoInitialize())try{return this.getOrInitializeService({instanceIdentifier:s})}catch(r){if(i)return null;throw r}else{if(i)return null;throw Error(`Service ${this.name} is not available`)}}getComponent(){return this.component}setComponent(e){if(e.name!==this.name)throw Error(`Mismatching Component ${e.name} for Provider ${this.name}.`);if(this.component)throw Error(`Component for ${this.name} has already been provided`);if(this.component=e,!!this.shouldAutoInitialize()){if(vk(e))try{this.getOrInitializeService({instanceIdentifier:ei})}catch{}for(const[n,s]of this.instancesDeferred.entries()){const i=this.normalizeInstanceIdentifier(n);try{const r=this.getOrInitializeService({instanceIdentifier:i});s.resolve(r)}catch{}}}}clearInstance(e=ei){this.instancesDeferred.delete(e),this.instancesOptions.delete(e),this.instances.delete(e)}async delete(){const e=Array.from(this.instances.values());await Promise.all([...e.filter(n=>"INTERNAL"in n).map(n=>n.INTERNAL.delete()),...e.filter(n=>"_delete"in n).map(n=>n._delete())])}isComponentSet(){return this.component!=null}isInitialized(e=ei){return this.instances.has(e)}getOptions(e=ei){return this.instancesOptions.get(e)||{}}initialize(e={}){const{options:n={}}=e,s=this.normalizeInstanceIdentifier(e.instanceIdentifier);if(this.isInitialized(s))throw Error(`${this.name}(${s}) has already been initialized`);if(!this.isComponentSet())throw Error(`Component ${this.name} has not been registered yet`);const i=this.getOrInitializeService({instanceIdentifier:s,options:n});for(const[r,o]of this.instancesDeferred.entries()){const l=this.normalizeInstanceIdentifier(r);s===l&&o.resolve(i)}return i}onInit(e,n){var s;const i=this.normalizeInstanceIdentifier(n),r=(s=this.onInitCallbacks.get(i))!==null&&s!==void 0?s:new Set;r.add(e),this.onInitCallbacks.set(i,r);const o=this.instances.get(i);return o&&e(o,i),()=>{r.delete(e)}}invokeOnInitCallbacks(e,n){const s=this.onInitCallbacks.get(n);if(s)for(const i of s)try{i(e,n)}catch{}}getOrInitializeService({instanceIdentifier:e,options:n={}}){let s=this.instances.get(e);if(!s&&this.component&&(s=this.component.instanceFactory(this.container,{instanceIdentifier:wk(e),options:n}),this.instances.set(e,s),this.instancesOptions.set(e,n),this.invokeOnInitCallbacks(s,e),this.component.onInstanceCreated))try{this.component.onInstanceCreated(this.container,e,s)}catch{}return s||null}normalizeInstanceIdentifier(e=ei){return this.component?this.component.multipleInstances?e:ei:e}shouldAutoInitialize(){return!!this.component&&this.component.instantiationMode!=="EXPLICIT"}}function wk(t){return t===ei?void 0:t}function vk(t){return t.instantiationMode==="EAGER"}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class bk{constructor(e){this.name=e,this.providers=new Map}addComponent(e){const n=this.getProvider(e.name);if(n.isComponentSet())throw new Error(`Component ${e.name} has already been registered with ${this.name}`);n.setComponent(e)}addOrOverwriteComponent(e){this.getProvider(e.name).isComponentSet()&&this.providers.delete(e.name),this.addComponent(e)}getProvider(e){if(this.providers.has(e))return this.providers.get(e);const n=new yk(e,this);return this.providers.set(e,n),n}getProviders(){return Array.from(this.providers.values())}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Ie;(function(t){t[t.DEBUG=0]="DEBUG",t[t.VERBOSE=1]="VERBOSE",t[t.INFO=2]="INFO",t[t.WARN=3]="WARN",t[t.ERROR=4]="ERROR",t[t.SILENT=5]="SILENT"})(Ie||(Ie={}));const Ek={debug:Ie.DEBUG,verbose:Ie.VERBOSE,info:Ie.INFO,warn:Ie.WARN,error:Ie.ERROR,silent:Ie.SILENT},Tk=Ie.INFO,Ck={[Ie.DEBUG]:"log",[Ie.VERBOSE]:"log",[Ie.INFO]:"info",[Ie.WARN]:"warn",[Ie.ERROR]:"error"},Ik=(t,e,...n)=>{if(e<t.logLevel)return;const s=new Date().toISOString(),i=Ck[e];if(i)console[i](`[${s}]  ${t.name}:`,...n);else throw new Error(`Attempted to log a message with an invalid logType (value: ${e})`)};class Uo{constructor(e){this.name=e,this._logLevel=Tk,this._logHandler=Ik,this._userLogHandler=null}get logLevel(){return this._logLevel}set logLevel(e){if(!(e in Ie))throw new TypeError(`Invalid value "${e}" assigned to \`logLevel\``);this._logLevel=e}setLogLevel(e){this._logLevel=typeof e=="string"?Ek[e]:e}get logHandler(){return this._logHandler}set logHandler(e){if(typeof e!="function")throw new TypeError("Value assigned to `logHandler` must be a function");this._logHandler=e}get userLogHandler(){return this._userLogHandler}set userLogHandler(e){this._userLogHandler=e}debug(...e){this._userLogHandler&&this._userLogHandler(this,Ie.DEBUG,...e),this._logHandler(this,Ie.DEBUG,...e)}log(...e){this._userLogHandler&&this._userLogHandler(this,Ie.VERBOSE,...e),this._logHandler(this,Ie.VERBOSE,...e)}info(...e){this._userLogHandler&&this._userLogHandler(this,Ie.INFO,...e),this._logHandler(this,Ie.INFO,...e)}warn(...e){this._userLogHandler&&this._userLogHandler(this,Ie.WARN,...e),this._logHandler(this,Ie.WARN,...e)}error(...e){this._userLogHandler&&this._userLogHandler(this,Ie.ERROR,...e),this._logHandler(this,Ie.ERROR,...e)}}const Sk=(t,e)=>e.some(n=>t instanceof n);let Eg,Tg;function Ak(){return Eg||(Eg=[IDBDatabase,IDBObjectStore,IDBIndex,IDBCursor,IDBTransaction])}function kk(){return Tg||(Tg=[IDBCursor.prototype.advance,IDBCursor.prototype.continue,IDBCursor.prototype.continuePrimaryKey])}const nv=new WeakMap,ch=new WeakMap,sv=new WeakMap,au=new WeakMap,Ad=new WeakMap;function Rk(t){const e=new Promise((n,s)=>{const i=()=>{t.removeEventListener("success",r),t.removeEventListener("error",o)},r=()=>{n(es(t.result)),i()},o=()=>{s(t.error),i()};t.addEventListener("success",r),t.addEventListener("error",o)});return e.then(n=>{n instanceof IDBCursor&&nv.set(n,t)}).catch(()=>{}),Ad.set(e,t),e}function Pk(t){if(ch.has(t))return;const e=new Promise((n,s)=>{const i=()=>{t.removeEventListener("complete",r),t.removeEventListener("error",o),t.removeEventListener("abort",o)},r=()=>{n(),i()},o=()=>{s(t.error||new DOMException("AbortError","AbortError")),i()};t.addEventListener("complete",r),t.addEventListener("error",o),t.addEventListener("abort",o)});ch.set(t,e)}let uh={get(t,e,n){if(t instanceof IDBTransaction){if(e==="done")return ch.get(t);if(e==="objectStoreNames")return t.objectStoreNames||sv.get(t);if(e==="store")return n.objectStoreNames[1]?void 0:n.objectStore(n.objectStoreNames[0])}return es(t[e])},set(t,e,n){return t[e]=n,!0},has(t,e){return t instanceof IDBTransaction&&(e==="done"||e==="store")?!0:e in t}};function Ok(t){uh=t(uh)}function Nk(t){return t===IDBDatabase.prototype.transaction&&!("objectStoreNames"in IDBTransaction.prototype)?function(e,...n){const s=t.call(lu(this),e,...n);return sv.set(s,e.sort?e.sort():[e]),es(s)}:kk().includes(t)?function(...e){return t.apply(lu(this),e),es(nv.get(this))}:function(...e){return es(t.apply(lu(this),e))}}function xk(t){return typeof t=="function"?Nk(t):(t instanceof IDBTransaction&&Pk(t),Sk(t,Ak())?new Proxy(t,uh):t)}function es(t){if(t instanceof IDBRequest)return Rk(t);if(au.has(t))return au.get(t);const e=xk(t);return e!==t&&(au.set(t,e),Ad.set(e,t)),e}const lu=t=>Ad.get(t);function oc(t,e,{blocked:n,upgrade:s,blocking:i,terminated:r}={}){const o=indexedDB.open(t,e),l=es(o);return s&&o.addEventListener("upgradeneeded",c=>{s(es(o.result),c.oldVersion,c.newVersion,es(o.transaction),c)}),n&&o.addEventListener("blocked",c=>n(c.oldVersion,c.newVersion,c)),l.then(c=>{r&&c.addEventListener("close",()=>r()),i&&c.addEventListener("versionchange",u=>i(u.oldVersion,u.newVersion,u))}).catch(()=>{}),l}function cu(t,{blocked:e}={}){const n=indexedDB.deleteDatabase(t);return e&&n.addEventListener("blocked",s=>e(s.oldVersion,s)),es(n).then(()=>{})}const Dk=["get","getKey","getAll","getAllKeys","count"],Lk=["put","add","delete","clear"],uu=new Map;function Cg(t,e){if(!(t instanceof IDBDatabase&&!(e in t)&&typeof e=="string"))return;if(uu.get(e))return uu.get(e);const n=e.replace(/FromIndex$/,""),s=e!==n,i=Lk.includes(n);if(!(n in(s?IDBIndex:IDBObjectStore).prototype)||!(i||Dk.includes(n)))return;const r=async function(o,...l){const c=this.transaction(o,i?"readwrite":"readonly");let u=c.store;return s&&(u=u.index(l.shift())),(await Promise.all([u[n](...l),i&&c.done]))[0]};return uu.set(e,r),r}Ok(t=>({...t,get:(e,n,s)=>Cg(e,n)||t.get(e,n,s),has:(e,n)=>!!Cg(e,n)||t.has(e,n)}));/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Mk{constructor(e){this.container=e}getPlatformInfoString(){return this.container.getProviders().map(n=>{if(Fk(n)){const s=n.getImmediate();return`${s.library}/${s.version}`}else return null}).filter(n=>n).join(" ")}}function Fk(t){const e=t.getComponent();return(e==null?void 0:e.type)==="VERSION"}const hh="@firebase/app",Ig="0.10.11";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const is=new Uo("@firebase/app"),Uk="@firebase/app-compat",$k="@firebase/analytics-compat",Hk="@firebase/analytics",Bk="@firebase/app-check-compat",jk="@firebase/app-check",Vk="@firebase/auth",Wk="@firebase/auth-compat",Kk="@firebase/database",qk="@firebase/database-compat",zk="@firebase/functions",Gk="@firebase/functions-compat",Yk="@firebase/installations",Xk="@firebase/installations-compat",Jk="@firebase/messaging",Qk="@firebase/messaging-compat",Zk="@firebase/performance",eR="@firebase/performance-compat",tR="@firebase/remote-config",nR="@firebase/remote-config-compat",sR="@firebase/storage",iR="@firebase/storage-compat",rR="@firebase/firestore",oR="@firebase/vertexai-preview",aR="@firebase/firestore-compat",lR="firebase",cR="10.13.2";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const dh="[DEFAULT]",uR={[hh]:"fire-core",[Uk]:"fire-core-compat",[Hk]:"fire-analytics",[$k]:"fire-analytics-compat",[jk]:"fire-app-check",[Bk]:"fire-app-check-compat",[Vk]:"fire-auth",[Wk]:"fire-auth-compat",[Kk]:"fire-rtdb",[qk]:"fire-rtdb-compat",[zk]:"fire-fn",[Gk]:"fire-fn-compat",[Yk]:"fire-iid",[Xk]:"fire-iid-compat",[Jk]:"fire-fcm",[Qk]:"fire-fcm-compat",[Zk]:"fire-perf",[eR]:"fire-perf-compat",[tR]:"fire-rc",[nR]:"fire-rc-compat",[sR]:"fire-gcs",[iR]:"fire-gcs-compat",[rR]:"fire-fst",[aR]:"fire-fst-compat",[oR]:"fire-vertex","fire-js":"fire-js",[lR]:"fire-js-all"};/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const fl=new Map,hR=new Map,fh=new Map;function Sg(t,e){try{t.container.addComponent(e)}catch(n){is.debug(`Component ${e.name} failed to register with FirebaseApp ${t.name}`,n)}}function Kt(t){const e=t.name;if(fh.has(e))return is.debug(`There were multiple attempts to register component ${e}.`),!1;fh.set(e,t);for(const n of fl.values())Sg(n,t);for(const n of hR.values())Sg(n,t);return!0}function $o(t,e){const n=t.container.getProvider("heartbeat").getImmediate({optional:!0});return n&&n.triggerHeartbeat(),t.container.getProvider(e)}function Rs(t){return t.settings!==void 0}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const dR={"no-app":"No Firebase App '{$appName}' has been created - call initializeApp() first","bad-app-name":"Illegal App name: '{$appName}'","duplicate-app":"Firebase App named '{$appName}' already exists with different options or config","app-deleted":"Firebase App named '{$appName}' already deleted","server-app-deleted":"Firebase Server App has been deleted","no-options":"Need to provide options, when not being deployed to hosting via source.","invalid-app-argument":"firebase.{$appName}() takes either no argument or a Firebase App instance.","invalid-log-argument":"First argument to `onLog` must be null or a function.","idb-open":"Error thrown when opening IndexedDB. Original error: {$originalErrorMessage}.","idb-get":"Error thrown when reading from IndexedDB. Original error: {$originalErrorMessage}.","idb-set":"Error thrown when writing to IndexedDB. Original error: {$originalErrorMessage}.","idb-delete":"Error thrown when deleting from IndexedDB. Original error: {$originalErrorMessage}.","finalization-registry-not-supported":"FirebaseServerApp deleteOnDeref field defined but the JS runtime does not support FinalizationRegistry.","invalid-server-app-environment":"FirebaseServerApp is not for use in browser environments."},Ds=new Bs("app","Firebase",dR);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class fR{constructor(e,n,s){this._isDeleted=!1,this._options=Object.assign({},e),this._config=Object.assign({},n),this._name=n.name,this._automaticDataCollectionEnabled=n.automaticDataCollectionEnabled,this._container=s,this.container.addComponent(new Mt("app",()=>this,"PUBLIC"))}get automaticDataCollectionEnabled(){return this.checkDestroyed(),this._automaticDataCollectionEnabled}set automaticDataCollectionEnabled(e){this.checkDestroyed(),this._automaticDataCollectionEnabled=e}get name(){return this.checkDestroyed(),this._name}get options(){return this.checkDestroyed(),this._options}get config(){return this.checkDestroyed(),this._config}get container(){return this._container}get isDeleted(){return this._isDeleted}set isDeleted(e){this._isDeleted=e}checkDestroyed(){if(this.isDeleted)throw Ds.create("app-deleted",{appName:this._name})}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const js=cR;function kd(t,e={}){let n=t;typeof e!="object"&&(e={name:e});const s=Object.assign({name:dh,automaticDataCollectionEnabled:!1},e),i=s.name;if(typeof i!="string"||!i)throw Ds.create("bad-app-name",{appName:String(i)});if(n||(n=Xw()),!n)throw Ds.create("no-options");const r=fl.get(i);if(r){if(dl(n,r.options)&&dl(s,r.config))return r;throw Ds.create("duplicate-app",{appName:i})}const o=new bk(i);for(const c of fh.values())o.addComponent(c);const l=new fR(n,s,o);return fl.set(i,l),l}function Rd(t=dh){const e=fl.get(t);if(!e&&t===dh&&Xw())return kd();if(!e)throw Ds.create("no-app",{appName:t});return e}function mt(t,e,n){var s;let i=(s=uR[t])!==null&&s!==void 0?s:t;n&&(i+=`-${n}`);const r=i.match(/\s|\//),o=e.match(/\s|\//);if(r||o){const l=[`Unable to register library "${i}" with version "${e}":`];r&&l.push(`library name "${i}" contains illegal characters (whitespace or "/")`),r&&o&&l.push("and"),o&&l.push(`version name "${e}" contains illegal characters (whitespace or "/")`),is.warn(l.join(" "));return}Kt(new Mt(`${i}-version`,()=>({library:i,version:e}),"VERSION"))}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const pR="firebase-heartbeat-database",gR=1,Eo="firebase-heartbeat-store";let hu=null;function iv(){return hu||(hu=oc(pR,gR,{upgrade:(t,e)=>{switch(e){case 0:try{t.createObjectStore(Eo)}catch(n){console.warn(n)}}}}).catch(t=>{throw Ds.create("idb-open",{originalErrorMessage:t.message})})),hu}async function mR(t){try{const n=(await iv()).transaction(Eo),s=await n.objectStore(Eo).get(rv(t));return await n.done,s}catch(e){if(e instanceof Sn)is.warn(e.message);else{const n=Ds.create("idb-get",{originalErrorMessage:e==null?void 0:e.message});is.warn(n.message)}}}async function Ag(t,e){try{const s=(await iv()).transaction(Eo,"readwrite");await s.objectStore(Eo).put(e,rv(t)),await s.done}catch(n){if(n instanceof Sn)is.warn(n.message);else{const s=Ds.create("idb-set",{originalErrorMessage:n==null?void 0:n.message});is.warn(s.message)}}}function rv(t){return`${t.name}!${t.options.appId}`}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const _R=1024,yR=30*24*60*60*1e3;class wR{constructor(e){this.container=e,this._heartbeatsCache=null;const n=this.container.getProvider("app").getImmediate();this._storage=new bR(n),this._heartbeatsCachePromise=this._storage.read().then(s=>(this._heartbeatsCache=s,s))}async triggerHeartbeat(){var e,n;try{const i=this.container.getProvider("platform-logger").getImmediate().getPlatformInfoString(),r=kg();return((e=this._heartbeatsCache)===null||e===void 0?void 0:e.heartbeats)==null&&(this._heartbeatsCache=await this._heartbeatsCachePromise,((n=this._heartbeatsCache)===null||n===void 0?void 0:n.heartbeats)==null)||this._heartbeatsCache.lastSentHeartbeatDate===r||this._heartbeatsCache.heartbeats.some(o=>o.date===r)?void 0:(this._heartbeatsCache.heartbeats.push({date:r,agent:i}),this._heartbeatsCache.heartbeats=this._heartbeatsCache.heartbeats.filter(o=>{const l=new Date(o.date).valueOf();return Date.now()-l<=yR}),this._storage.overwrite(this._heartbeatsCache))}catch(s){is.warn(s)}}async getHeartbeatsHeader(){var e;try{if(this._heartbeatsCache===null&&await this._heartbeatsCachePromise,((e=this._heartbeatsCache)===null||e===void 0?void 0:e.heartbeats)==null||this._heartbeatsCache.heartbeats.length===0)return"";const n=kg(),{heartbeatsToSend:s,unsentEntries:i}=vR(this._heartbeatsCache.heartbeats),r=Gw(JSON.stringify({version:2,heartbeats:s}));return this._heartbeatsCache.lastSentHeartbeatDate=n,i.length>0?(this._heartbeatsCache.heartbeats=i,await this._storage.overwrite(this._heartbeatsCache)):(this._heartbeatsCache.heartbeats=[],this._storage.overwrite(this._heartbeatsCache)),r}catch(n){return is.warn(n),""}}}function kg(){return new Date().toISOString().substring(0,10)}function vR(t,e=_R){const n=[];let s=t.slice();for(const i of t){const r=n.find(o=>o.agent===i.agent);if(r){if(r.dates.push(i.date),Rg(n)>e){r.dates.pop();break}}else if(n.push({agent:i.agent,dates:[i.date]}),Rg(n)>e){n.pop();break}s=s.slice(1)}return{heartbeatsToSend:n,unsentEntries:s}}class bR{constructor(e){this.app=e,this._canUseIndexedDBPromise=this.runIndexedDBEnvironmentCheck()}async runIndexedDBEnvironmentCheck(){return Sd()?ev().then(()=>!0).catch(()=>!1):!1}async read(){if(await this._canUseIndexedDBPromise){const n=await mR(this.app);return n!=null&&n.heartbeats?n:{heartbeats:[]}}else return{heartbeats:[]}}async overwrite(e){var n;if(await this._canUseIndexedDBPromise){const i=await this.read();return Ag(this.app,{lastSentHeartbeatDate:(n=e.lastSentHeartbeatDate)!==null&&n!==void 0?n:i.lastSentHeartbeatDate,heartbeats:e.heartbeats})}else return}async add(e){var n;if(await this._canUseIndexedDBPromise){const i=await this.read();return Ag(this.app,{lastSentHeartbeatDate:(n=e.lastSentHeartbeatDate)!==null&&n!==void 0?n:i.lastSentHeartbeatDate,heartbeats:[...i.heartbeats,...e.heartbeats]})}else return}}function Rg(t){return Gw(JSON.stringify({version:2,heartbeats:t})).length}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function ER(t){Kt(new Mt("platform-logger",e=>new Mk(e),"PRIVATE")),Kt(new Mt("heartbeat",e=>new wR(e),"PRIVATE")),mt(hh,Ig,t),mt(hh,Ig,"esm2017"),mt("fire-js","")}ER("");var Pg=typeof globalThis<"u"?globalThis:typeof window<"u"?window:typeof global<"u"?global:typeof self<"u"?self:{};/** @license
Copyright The Closure Library Authors.
SPDX-License-Identifier: Apache-2.0
*/var ov;(function(){var t;/** @license

 Copyright The Closure Library Authors.
 SPDX-License-Identifier: Apache-2.0
*/function e(T,w){function y(){}y.prototype=w.prototype,T.D=w.prototype,T.prototype=new y,T.prototype.constructor=T,T.C=function(v,A,S){for(var E=Array(arguments.length-2),he=2;he<arguments.length;he++)E[he-2]=arguments[he];return w.prototype[A].apply(v,E)}}function n(){this.blockSize=-1}function s(){this.blockSize=-1,this.blockSize=64,this.g=Array(4),this.B=Array(this.blockSize),this.o=this.h=0,this.s()}e(s,n),s.prototype.s=function(){this.g[0]=1732584193,this.g[1]=4023233417,this.g[2]=2562383102,this.g[3]=271733878,this.o=this.h=0};function i(T,w,y){y||(y=0);var v=Array(16);if(typeof w=="string")for(var A=0;16>A;++A)v[A]=w.charCodeAt(y++)|w.charCodeAt(y++)<<8|w.charCodeAt(y++)<<16|w.charCodeAt(y++)<<24;else for(A=0;16>A;++A)v[A]=w[y++]|w[y++]<<8|w[y++]<<16|w[y++]<<24;w=T.g[0],y=T.g[1],A=T.g[2];var S=T.g[3],E=w+(S^y&(A^S))+v[0]+3614090360&4294967295;w=y+(E<<7&4294967295|E>>>25),E=S+(A^w&(y^A))+v[1]+3905402710&4294967295,S=w+(E<<12&4294967295|E>>>20),E=A+(y^S&(w^y))+v[2]+606105819&4294967295,A=S+(E<<17&4294967295|E>>>15),E=y+(w^A&(S^w))+v[3]+3250441966&4294967295,y=A+(E<<22&4294967295|E>>>10),E=w+(S^y&(A^S))+v[4]+4118548399&4294967295,w=y+(E<<7&4294967295|E>>>25),E=S+(A^w&(y^A))+v[5]+1200080426&4294967295,S=w+(E<<12&4294967295|E>>>20),E=A+(y^S&(w^y))+v[6]+2821735955&4294967295,A=S+(E<<17&4294967295|E>>>15),E=y+(w^A&(S^w))+v[7]+4249261313&4294967295,y=A+(E<<22&4294967295|E>>>10),E=w+(S^y&(A^S))+v[8]+1770035416&4294967295,w=y+(E<<7&4294967295|E>>>25),E=S+(A^w&(y^A))+v[9]+2336552879&4294967295,S=w+(E<<12&4294967295|E>>>20),E=A+(y^S&(w^y))+v[10]+4294925233&4294967295,A=S+(E<<17&4294967295|E>>>15),E=y+(w^A&(S^w))+v[11]+2304563134&4294967295,y=A+(E<<22&4294967295|E>>>10),E=w+(S^y&(A^S))+v[12]+1804603682&4294967295,w=y+(E<<7&4294967295|E>>>25),E=S+(A^w&(y^A))+v[13]+4254626195&4294967295,S=w+(E<<12&4294967295|E>>>20),E=A+(y^S&(w^y))+v[14]+2792965006&4294967295,A=S+(E<<17&4294967295|E>>>15),E=y+(w^A&(S^w))+v[15]+1236535329&4294967295,y=A+(E<<22&4294967295|E>>>10),E=w+(A^S&(y^A))+v[1]+4129170786&4294967295,w=y+(E<<5&4294967295|E>>>27),E=S+(y^A&(w^y))+v[6]+3225465664&4294967295,S=w+(E<<9&4294967295|E>>>23),E=A+(w^y&(S^w))+v[11]+643717713&4294967295,A=S+(E<<14&4294967295|E>>>18),E=y+(S^w&(A^S))+v[0]+3921069994&4294967295,y=A+(E<<20&4294967295|E>>>12),E=w+(A^S&(y^A))+v[5]+3593408605&4294967295,w=y+(E<<5&4294967295|E>>>27),E=S+(y^A&(w^y))+v[10]+38016083&4294967295,S=w+(E<<9&4294967295|E>>>23),E=A+(w^y&(S^w))+v[15]+3634488961&4294967295,A=S+(E<<14&4294967295|E>>>18),E=y+(S^w&(A^S))+v[4]+3889429448&4294967295,y=A+(E<<20&4294967295|E>>>12),E=w+(A^S&(y^A))+v[9]+568446438&4294967295,w=y+(E<<5&4294967295|E>>>27),E=S+(y^A&(w^y))+v[14]+3275163606&4294967295,S=w+(E<<9&4294967295|E>>>23),E=A+(w^y&(S^w))+v[3]+4107603335&4294967295,A=S+(E<<14&4294967295|E>>>18),E=y+(S^w&(A^S))+v[8]+1163531501&4294967295,y=A+(E<<20&4294967295|E>>>12),E=w+(A^S&(y^A))+v[13]+2850285829&4294967295,w=y+(E<<5&4294967295|E>>>27),E=S+(y^A&(w^y))+v[2]+4243563512&4294967295,S=w+(E<<9&4294967295|E>>>23),E=A+(w^y&(S^w))+v[7]+1735328473&4294967295,A=S+(E<<14&4294967295|E>>>18),E=y+(S^w&(A^S))+v[12]+2368359562&4294967295,y=A+(E<<20&4294967295|E>>>12),E=w+(y^A^S)+v[5]+4294588738&4294967295,w=y+(E<<4&4294967295|E>>>28),E=S+(w^y^A)+v[8]+2272392833&4294967295,S=w+(E<<11&4294967295|E>>>21),E=A+(S^w^y)+v[11]+1839030562&4294967295,A=S+(E<<16&4294967295|E>>>16),E=y+(A^S^w)+v[14]+4259657740&4294967295,y=A+(E<<23&4294967295|E>>>9),E=w+(y^A^S)+v[1]+2763975236&4294967295,w=y+(E<<4&4294967295|E>>>28),E=S+(w^y^A)+v[4]+1272893353&4294967295,S=w+(E<<11&4294967295|E>>>21),E=A+(S^w^y)+v[7]+4139469664&4294967295,A=S+(E<<16&4294967295|E>>>16),E=y+(A^S^w)+v[10]+3200236656&4294967295,y=A+(E<<23&4294967295|E>>>9),E=w+(y^A^S)+v[13]+681279174&4294967295,w=y+(E<<4&4294967295|E>>>28),E=S+(w^y^A)+v[0]+3936430074&4294967295,S=w+(E<<11&4294967295|E>>>21),E=A+(S^w^y)+v[3]+3572445317&4294967295,A=S+(E<<16&4294967295|E>>>16),E=y+(A^S^w)+v[6]+76029189&4294967295,y=A+(E<<23&4294967295|E>>>9),E=w+(y^A^S)+v[9]+3654602809&4294967295,w=y+(E<<4&4294967295|E>>>28),E=S+(w^y^A)+v[12]+3873151461&4294967295,S=w+(E<<11&4294967295|E>>>21),E=A+(S^w^y)+v[15]+530742520&4294967295,A=S+(E<<16&4294967295|E>>>16),E=y+(A^S^w)+v[2]+3299628645&4294967295,y=A+(E<<23&4294967295|E>>>9),E=w+(A^(y|~S))+v[0]+4096336452&4294967295,w=y+(E<<6&4294967295|E>>>26),E=S+(y^(w|~A))+v[7]+1126891415&4294967295,S=w+(E<<10&4294967295|E>>>22),E=A+(w^(S|~y))+v[14]+2878612391&4294967295,A=S+(E<<15&4294967295|E>>>17),E=y+(S^(A|~w))+v[5]+4237533241&4294967295,y=A+(E<<21&4294967295|E>>>11),E=w+(A^(y|~S))+v[12]+1700485571&4294967295,w=y+(E<<6&4294967295|E>>>26),E=S+(y^(w|~A))+v[3]+2399980690&4294967295,S=w+(E<<10&4294967295|E>>>22),E=A+(w^(S|~y))+v[10]+4293915773&4294967295,A=S+(E<<15&4294967295|E>>>17),E=y+(S^(A|~w))+v[1]+2240044497&4294967295,y=A+(E<<21&4294967295|E>>>11),E=w+(A^(y|~S))+v[8]+1873313359&4294967295,w=y+(E<<6&4294967295|E>>>26),E=S+(y^(w|~A))+v[15]+4264355552&4294967295,S=w+(E<<10&4294967295|E>>>22),E=A+(w^(S|~y))+v[6]+2734768916&4294967295,A=S+(E<<15&4294967295|E>>>17),E=y+(S^(A|~w))+v[13]+1309151649&4294967295,y=A+(E<<21&4294967295|E>>>11),E=w+(A^(y|~S))+v[4]+4149444226&4294967295,w=y+(E<<6&4294967295|E>>>26),E=S+(y^(w|~A))+v[11]+3174756917&4294967295,S=w+(E<<10&4294967295|E>>>22),E=A+(w^(S|~y))+v[2]+718787259&4294967295,A=S+(E<<15&4294967295|E>>>17),E=y+(S^(A|~w))+v[9]+3951481745&4294967295,T.g[0]=T.g[0]+w&4294967295,T.g[1]=T.g[1]+(A+(E<<21&4294967295|E>>>11))&4294967295,T.g[2]=T.g[2]+A&4294967295,T.g[3]=T.g[3]+S&4294967295}s.prototype.u=function(T,w){w===void 0&&(w=T.length);for(var y=w-this.blockSize,v=this.B,A=this.h,S=0;S<w;){if(A==0)for(;S<=y;)i(this,T,S),S+=this.blockSize;if(typeof T=="string"){for(;S<w;)if(v[A++]=T.charCodeAt(S++),A==this.blockSize){i(this,v),A=0;break}}else for(;S<w;)if(v[A++]=T[S++],A==this.blockSize){i(this,v),A=0;break}}this.h=A,this.o+=w},s.prototype.v=function(){var T=Array((56>this.h?this.blockSize:2*this.blockSize)-this.h);T[0]=128;for(var w=1;w<T.length-8;++w)T[w]=0;var y=8*this.o;for(w=T.length-8;w<T.length;++w)T[w]=y&255,y/=256;for(this.u(T),T=Array(16),w=y=0;4>w;++w)for(var v=0;32>v;v+=8)T[y++]=this.g[w]>>>v&255;return T};function r(T,w){var y=l;return Object.prototype.hasOwnProperty.call(y,T)?y[T]:y[T]=w(T)}function o(T,w){this.h=w;for(var y=[],v=!0,A=T.length-1;0<=A;A--){var S=T[A]|0;v&&S==w||(y[A]=S,v=!1)}this.g=y}var l={};function c(T){return-128<=T&&128>T?r(T,function(w){return new o([w|0],0>w?-1:0)}):new o([T|0],0>T?-1:0)}function u(T){if(isNaN(T)||!isFinite(T))return f;if(0>T)return D(u(-T));for(var w=[],y=1,v=0;T>=y;v++)w[v]=T/y|0,y*=4294967296;return new o(w,0)}function h(T,w){if(T.length==0)throw Error("number format error: empty string");if(w=w||10,2>w||36<w)throw Error("radix out of range: "+w);if(T.charAt(0)=="-")return D(h(T.substring(1),w));if(0<=T.indexOf("-"))throw Error('number format error: interior "-" character');for(var y=u(Math.pow(w,8)),v=f,A=0;A<T.length;A+=8){var S=Math.min(8,T.length-A),E=parseInt(T.substring(A,A+S),w);8>S?(S=u(Math.pow(w,S)),v=v.j(S).add(u(E))):(v=v.j(y),v=v.add(u(E)))}return v}var f=c(0),g=c(1),m=c(16777216);t=o.prototype,t.m=function(){if(P(this))return-D(this).m();for(var T=0,w=1,y=0;y<this.g.length;y++){var v=this.i(y);T+=(0<=v?v:4294967296+v)*w,w*=4294967296}return T},t.toString=function(T){if(T=T||10,2>T||36<T)throw Error("radix out of range: "+T);if(I(this))return"0";if(P(this))return"-"+D(this).toString(T);for(var w=u(Math.pow(T,6)),y=this,v="";;){var A=R(y,w).g;y=M(y,A.j(w));var S=((0<y.g.length?y.g[0]:y.h)>>>0).toString(T);if(y=A,I(y))return S+v;for(;6>S.length;)S="0"+S;v=S+v}},t.i=function(T){return 0>T?0:T<this.g.length?this.g[T]:this.h};function I(T){if(T.h!=0)return!1;for(var w=0;w<T.g.length;w++)if(T.g[w]!=0)return!1;return!0}function P(T){return T.h==-1}t.l=function(T){return T=M(this,T),P(T)?-1:I(T)?0:1};function D(T){for(var w=T.g.length,y=[],v=0;v<w;v++)y[v]=~T.g[v];return new o(y,~T.h).add(g)}t.abs=function(){return P(this)?D(this):this},t.add=function(T){for(var w=Math.max(this.g.length,T.g.length),y=[],v=0,A=0;A<=w;A++){var S=v+(this.i(A)&65535)+(T.i(A)&65535),E=(S>>>16)+(this.i(A)>>>16)+(T.i(A)>>>16);v=E>>>16,S&=65535,E&=65535,y[A]=E<<16|S}return new o(y,y[y.length-1]&-2147483648?-1:0)};function M(T,w){return T.add(D(w))}t.j=function(T){if(I(this)||I(T))return f;if(P(this))return P(T)?D(this).j(D(T)):D(D(this).j(T));if(P(T))return D(this.j(D(T)));if(0>this.l(m)&&0>T.l(m))return u(this.m()*T.m());for(var w=this.g.length+T.g.length,y=[],v=0;v<2*w;v++)y[v]=0;for(v=0;v<this.g.length;v++)for(var A=0;A<T.g.length;A++){var S=this.i(v)>>>16,E=this.i(v)&65535,he=T.i(A)>>>16,pe=T.i(A)&65535;y[2*v+2*A]+=E*pe,x(y,2*v+2*A),y[2*v+2*A+1]+=S*pe,x(y,2*v+2*A+1),y[2*v+2*A+1]+=E*he,x(y,2*v+2*A+1),y[2*v+2*A+2]+=S*he,x(y,2*v+2*A+2)}for(v=0;v<w;v++)y[v]=y[2*v+1]<<16|y[2*v];for(v=w;v<2*w;v++)y[v]=0;return new o(y,0)};function x(T,w){for(;(T[w]&65535)!=T[w];)T[w+1]+=T[w]>>>16,T[w]&=65535,w++}function b(T,w){this.g=T,this.h=w}function R(T,w){if(I(w))throw Error("division by zero");if(I(T))return new b(f,f);if(P(T))return w=R(D(T),w),new b(D(w.g),D(w.h));if(P(w))return w=R(T,D(w)),new b(D(w.g),w.h);if(30<T.g.length){if(P(T)||P(w))throw Error("slowDivide_ only works with positive integers.");for(var y=g,v=w;0>=v.l(T);)y=N(y),v=N(v);var A=F(y,1),S=F(v,1);for(v=F(v,2),y=F(y,2);!I(v);){var E=S.add(v);0>=E.l(T)&&(A=A.add(y),S=E),v=F(v,1),y=F(y,1)}return w=M(T,A.j(w)),new b(A,w)}for(A=f;0<=T.l(w);){for(y=Math.max(1,Math.floor(T.m()/w.m())),v=Math.ceil(Math.log(y)/Math.LN2),v=48>=v?1:Math.pow(2,v-48),S=u(y),E=S.j(w);P(E)||0<E.l(T);)y-=v,S=u(y),E=S.j(w);I(S)&&(S=g),A=A.add(S),T=M(T,E)}return new b(A,T)}t.A=function(T){return R(this,T).h},t.and=function(T){for(var w=Math.max(this.g.length,T.g.length),y=[],v=0;v<w;v++)y[v]=this.i(v)&T.i(v);return new o(y,this.h&T.h)},t.or=function(T){for(var w=Math.max(this.g.length,T.g.length),y=[],v=0;v<w;v++)y[v]=this.i(v)|T.i(v);return new o(y,this.h|T.h)},t.xor=function(T){for(var w=Math.max(this.g.length,T.g.length),y=[],v=0;v<w;v++)y[v]=this.i(v)^T.i(v);return new o(y,this.h^T.h)};function N(T){for(var w=T.g.length+1,y=[],v=0;v<w;v++)y[v]=T.i(v)<<1|T.i(v-1)>>>31;return new o(y,T.h)}function F(T,w){var y=w>>5;w%=32;for(var v=T.g.length-y,A=[],S=0;S<v;S++)A[S]=0<w?T.i(S+y)>>>w|T.i(S+y+1)<<32-w:T.i(S+y);return new o(A,T.h)}s.prototype.digest=s.prototype.v,s.prototype.reset=s.prototype.s,s.prototype.update=s.prototype.u,o.prototype.add=o.prototype.add,o.prototype.multiply=o.prototype.j,o.prototype.modulo=o.prototype.A,o.prototype.compare=o.prototype.l,o.prototype.toNumber=o.prototype.m,o.prototype.toString=o.prototype.toString,o.prototype.getBits=o.prototype.i,o.fromNumber=u,o.fromString=h,ov=o}).apply(typeof Pg<"u"?Pg:typeof self<"u"?self:typeof window<"u"?window:{});var ka=typeof globalThis<"u"?globalThis:typeof window<"u"?window:typeof global<"u"?global:typeof self<"u"?self:{};(function(){var t,e=typeof Object.defineProperties=="function"?Object.defineProperty:function(a,d,p){return a==Array.prototype||a==Object.prototype||(a[d]=p.value),a};function n(a){a=[typeof globalThis=="object"&&globalThis,a,typeof window=="object"&&window,typeof self=="object"&&self,typeof ka=="object"&&ka];for(var d=0;d<a.length;++d){var p=a[d];if(p&&p.Math==Math)return p}throw Error("Cannot find global object")}var s=n(this);function i(a,d){if(d)e:{var p=s;a=a.split(".");for(var _=0;_<a.length-1;_++){var O=a[_];if(!(O in p))break e;p=p[O]}a=a[a.length-1],_=p[a],d=d(_),d!=_&&d!=null&&e(p,a,{configurable:!0,writable:!0,value:d})}}function r(a,d){a instanceof String&&(a+="");var p=0,_=!1,O={next:function(){if(!_&&p<a.length){var U=p++;return{value:d(U,a[U]),done:!1}}return _=!0,{done:!0,value:void 0}}};return O[Symbol.iterator]=function(){return O},O}i("Array.prototype.values",function(a){return a||function(){return r(this,function(d,p){return p})}});/** @license

 Copyright The Closure Library Authors.
 SPDX-License-Identifier: Apache-2.0
*/var o=o||{},l=this||self;function c(a){var d=typeof a;return d=d!="object"?d:a?Array.isArray(a)?"array":d:"null",d=="array"||d=="object"&&typeof a.length=="number"}function u(a){var d=typeof a;return d=="object"&&a!=null||d=="function"}function h(a,d,p){return a.call.apply(a.bind,arguments)}function f(a,d,p){if(!a)throw Error();if(2<arguments.length){var _=Array.prototype.slice.call(arguments,2);return function(){var O=Array.prototype.slice.call(arguments);return Array.prototype.unshift.apply(O,_),a.apply(d,O)}}return function(){return a.apply(d,arguments)}}function g(a,d,p){return g=Function.prototype.bind&&Function.prototype.bind.toString().indexOf("native code")!=-1?h:f,g.apply(null,arguments)}function m(a,d){var p=Array.prototype.slice.call(arguments,1);return function(){var _=p.slice();return _.push.apply(_,arguments),a.apply(this,_)}}function I(a,d){function p(){}p.prototype=d.prototype,a.aa=d.prototype,a.prototype=new p,a.prototype.constructor=a,a.Qb=function(_,O,U){for(var z=Array(arguments.length-2),Ne=2;Ne<arguments.length;Ne++)z[Ne-2]=arguments[Ne];return d.prototype[O].apply(_,z)}}function P(a){const d=a.length;if(0<d){const p=Array(d);for(let _=0;_<d;_++)p[_]=a[_];return p}return[]}function D(a,d){for(let p=1;p<arguments.length;p++){const _=arguments[p];if(c(_)){const O=a.length||0,U=_.length||0;a.length=O+U;for(let z=0;z<U;z++)a[O+z]=_[z]}else a.push(_)}}class M{constructor(d,p){this.i=d,this.j=p,this.h=0,this.g=null}get(){let d;return 0<this.h?(this.h--,d=this.g,this.g=d.next,d.next=null):d=this.i(),d}}function x(a){return/^[\s\xa0]*$/.test(a)}function b(){var a=l.navigator;return a&&(a=a.userAgent)?a:""}function R(a){return R[" "](a),a}R[" "]=function(){};var N=b().indexOf("Gecko")!=-1&&!(b().toLowerCase().indexOf("webkit")!=-1&&b().indexOf("Edge")==-1)&&!(b().indexOf("Trident")!=-1||b().indexOf("MSIE")!=-1)&&b().indexOf("Edge")==-1;function F(a,d,p){for(const _ in a)d.call(p,a[_],_,a)}function T(a,d){for(const p in a)d.call(void 0,a[p],p,a)}function w(a){const d={};for(const p in a)d[p]=a[p];return d}const y="constructor hasOwnProperty isPrototypeOf propertyIsEnumerable toLocaleString toString valueOf".split(" ");function v(a,d){let p,_;for(let O=1;O<arguments.length;O++){_=arguments[O];for(p in _)a[p]=_[p];for(let U=0;U<y.length;U++)p=y[U],Object.prototype.hasOwnProperty.call(_,p)&&(a[p]=_[p])}}function A(a){var d=1;a=a.split(":");const p=[];for(;0<d&&a.length;)p.push(a.shift()),d--;return a.length&&p.push(a.join(":")),p}function S(a){l.setTimeout(()=>{throw a},0)}function E(){var a=Ve;let d=null;return a.g&&(d=a.g,a.g=a.g.next,a.g||(a.h=null),d.next=null),d}class he{constructor(){this.h=this.g=null}add(d,p){const _=pe.get();_.set(d,p),this.h?this.h.next=_:this.g=_,this.h=_}}var pe=new M(()=>new ee,a=>a.reset());class ee{constructor(){this.next=this.g=this.h=null}set(d,p){this.h=d,this.g=p,this.next=null}reset(){this.next=this.g=this.h=null}}let le,Z=!1,Ve=new he,fn=()=>{const a=l.Promise.resolve(void 0);le=()=>{a.then(tn)}};var tn=()=>{for(var a;a=E();){try{a.h.call(a.g)}catch(p){S(p)}var d=pe;d.j(a),100>d.h&&(d.h++,a.next=d.g,d.g=a)}Z=!1};function We(){this.s=this.s,this.C=this.C}We.prototype.s=!1,We.prototype.ma=function(){this.s||(this.s=!0,this.N())},We.prototype.N=function(){if(this.C)for(;this.C.length;)this.C.shift()()};function Ke(a,d){this.type=a,this.g=this.target=d,this.defaultPrevented=!1}Ke.prototype.h=function(){this.defaultPrevented=!0};var hs=function(){if(!l.addEventListener||!Object.defineProperty)return!1;var a=!1,d=Object.defineProperty({},"passive",{get:function(){a=!0}});try{const p=()=>{};l.addEventListener("test",p,d),l.removeEventListener("test",p,d)}catch{}return a}();function An(a,d){if(Ke.call(this,a?a.type:""),this.relatedTarget=this.g=this.target=null,this.button=this.screenY=this.screenX=this.clientY=this.clientX=0,this.key="",this.metaKey=this.shiftKey=this.altKey=this.ctrlKey=!1,this.state=null,this.pointerId=0,this.pointerType="",this.i=null,a){var p=this.type=a.type,_=a.changedTouches&&a.changedTouches.length?a.changedTouches[0]:null;if(this.target=a.target||a.srcElement,this.g=d,d=a.relatedTarget){if(N){e:{try{R(d.nodeName);var O=!0;break e}catch{}O=!1}O||(d=null)}}else p=="mouseover"?d=a.fromElement:p=="mouseout"&&(d=a.toElement);this.relatedTarget=d,_?(this.clientX=_.clientX!==void 0?_.clientX:_.pageX,this.clientY=_.clientY!==void 0?_.clientY:_.pageY,this.screenX=_.screenX||0,this.screenY=_.screenY||0):(this.clientX=a.clientX!==void 0?a.clientX:a.pageX,this.clientY=a.clientY!==void 0?a.clientY:a.pageY,this.screenX=a.screenX||0,this.screenY=a.screenY||0),this.button=a.button,this.key=a.key||"",this.ctrlKey=a.ctrlKey,this.altKey=a.altKey,this.shiftKey=a.shiftKey,this.metaKey=a.metaKey,this.pointerId=a.pointerId||0,this.pointerType=typeof a.pointerType=="string"?a.pointerType:Ft[a.pointerType]||"",this.state=a.state,this.i=a,a.defaultPrevented&&An.aa.h.call(this)}}I(An,Ke);var Ft={2:"touch",3:"pen",4:"mouse"};An.prototype.h=function(){An.aa.h.call(this);var a=this.i;a.preventDefault?a.preventDefault():a.returnValue=!1};var B="closure_listenable_"+(1e6*Math.random()|0),Q=0;function X(a,d,p,_,O){this.listener=a,this.proxy=null,this.src=d,this.type=p,this.capture=!!_,this.ha=O,this.key=++Q,this.da=this.fa=!1}function te(a){a.da=!0,a.listener=null,a.proxy=null,a.src=null,a.ha=null}function de(a){this.src=a,this.g={},this.h=0}de.prototype.add=function(a,d,p,_,O){var U=a.toString();a=this.g[U],a||(a=this.g[U]=[],this.h++);var z=C(a,d,_,O);return-1<z?(d=a[z],p||(d.fa=!1)):(d=new X(d,this.src,U,!!_,O),d.fa=p,a.push(d)),d};function Re(a,d){var p=d.type;if(p in a.g){var _=a.g[p],O=Array.prototype.indexOf.call(_,d,void 0),U;(U=0<=O)&&Array.prototype.splice.call(_,O,1),U&&(te(d),a.g[p].length==0&&(delete a.g[p],a.h--))}}function C(a,d,p,_){for(var O=0;O<a.length;++O){var U=a[O];if(!U.da&&U.listener==d&&U.capture==!!p&&U.ha==_)return O}return-1}var k="closure_lm_"+(1e6*Math.random()|0),L={};function j(a,d,p,_,O){if(Array.isArray(d)){for(var U=0;U<d.length;U++)j(a,d[U],p,_,O);return null}return p=se(p),a&&a[B]?a.K(d,p,u(_)?!!_.capture:!!_,O):H(a,d,p,!1,_,O)}function H(a,d,p,_,O,U){if(!d)throw Error("Invalid event type");var z=u(O)?!!O.capture:!!O,Ne=ie(a);if(Ne||(a[k]=Ne=new de(a)),p=Ne.add(d,p,_,z,U),p.proxy)return p;if(_=V(),p.proxy=_,_.src=a,_.listener=p,a.addEventListener)hs||(O=z),O===void 0&&(O=!1),a.addEventListener(d.toString(),_,O);else if(a.attachEvent)a.attachEvent(K(d.toString()),_);else if(a.addListener&&a.removeListener)a.addListener(_);else throw Error("addEventListener and attachEvent are unavailable.");return p}function V(){function a(p){return d.call(a.src,a.listener,p)}const d=W;return a}function G(a,d,p,_,O){if(Array.isArray(d))for(var U=0;U<d.length;U++)G(a,d[U],p,_,O);else _=u(_)?!!_.capture:!!_,p=se(p),a&&a[B]?(a=a.i,d=String(d).toString(),d in a.g&&(U=a.g[d],p=C(U,p,_,O),-1<p&&(te(U[p]),Array.prototype.splice.call(U,p,1),U.length==0&&(delete a.g[d],a.h--)))):a&&(a=ie(a))&&(d=a.g[d.toString()],a=-1,d&&(a=C(d,p,_,O)),(p=-1<a?d[a]:null)&&q(p))}function q(a){if(typeof a!="number"&&a&&!a.da){var d=a.src;if(d&&d[B])Re(d.i,a);else{var p=a.type,_=a.proxy;d.removeEventListener?d.removeEventListener(p,_,a.capture):d.detachEvent?d.detachEvent(K(p),_):d.addListener&&d.removeListener&&d.removeListener(_),(p=ie(d))?(Re(p,a),p.h==0&&(p.src=null,d[k]=null)):te(a)}}}function K(a){return a in L?L[a]:L[a]="on"+a}function W(a,d){if(a.da)a=!0;else{d=new An(d,this);var p=a.listener,_=a.ha||a.src;a.fa&&q(a),a=p.call(_,d)}return a}function ie(a){return a=a[k],a instanceof de?a:null}var J="__closure_events_fn_"+(1e9*Math.random()>>>0);function se(a){return typeof a=="function"?a:(a[J]||(a[J]=function(d){return a.handleEvent(d)}),a[J])}function ne(){We.call(this),this.i=new de(this),this.M=this,this.F=null}I(ne,We),ne.prototype[B]=!0,ne.prototype.removeEventListener=function(a,d,p,_){G(this,a,d,p,_)};function ae(a,d){var p,_=a.F;if(_)for(p=[];_;_=_.F)p.push(_);if(a=a.M,_=d.type||d,typeof d=="string")d=new Ke(d,a);else if(d instanceof Ke)d.target=d.target||a;else{var O=d;d=new Ke(_,a),v(d,O)}if(O=!0,p)for(var U=p.length-1;0<=U;U--){var z=d.g=p[U];O=Ee(z,_,!0,d)&&O}if(z=d.g=a,O=Ee(z,_,!0,d)&&O,O=Ee(z,_,!1,d)&&O,p)for(U=0;U<p.length;U++)z=d.g=p[U],O=Ee(z,_,!1,d)&&O}ne.prototype.N=function(){if(ne.aa.N.call(this),this.i){var a=this.i,d;for(d in a.g){for(var p=a.g[d],_=0;_<p.length;_++)te(p[_]);delete a.g[d],a.h--}}this.F=null},ne.prototype.K=function(a,d,p,_){return this.i.add(String(a),d,!1,p,_)},ne.prototype.L=function(a,d,p,_){return this.i.add(String(a),d,!0,p,_)};function Ee(a,d,p,_){if(d=a.i.g[String(d)],!d)return!0;d=d.concat();for(var O=!0,U=0;U<d.length;++U){var z=d[U];if(z&&!z.da&&z.capture==p){var Ne=z.listener,pt=z.ha||z.src;z.fa&&Re(a.i,z),O=Ne.call(pt,_)!==!1&&O}}return O&&!_.defaultPrevented}function ye(a,d,p){if(typeof a=="function")p&&(a=g(a,p));else if(a&&typeof a.handleEvent=="function")a=g(a.handleEvent,a);else throw Error("Invalid listener argument");return 2147483647<Number(d)?-1:l.setTimeout(a,d||0)}function _t(a){a.g=ye(()=>{a.g=null,a.i&&(a.i=!1,_t(a))},a.l);const d=a.h;a.h=null,a.m.apply(null,d)}class rt extends We{constructor(d,p){super(),this.m=d,this.l=p,this.h=null,this.i=!1,this.g=null}j(d){this.h=arguments,this.g?this.i=!0:_t(this)}N(){super.N(),this.g&&(l.clearTimeout(this.g),this.g=null,this.i=!1,this.h=null)}}function ft(a){We.call(this),this.h=a,this.g={}}I(ft,We);var yt=[];function ds(a){F(a.g,function(d,p){this.g.hasOwnProperty(p)&&q(d)},a),a.g={}}ft.prototype.N=function(){ft.aa.N.call(this),ds(this)},ft.prototype.handleEvent=function(){throw Error("EventHandler.handleEvent not implemented")};var ki=l.JSON.stringify,Pt=l.JSON.parse,nn=class{stringify(a){return l.JSON.stringify(a,void 0)}parse(a){return l.JSON.parse(a,void 0)}};function Ri(){}Ri.prototype.h=null;function Lf(a){return a.h||(a.h=a.i())}function hT(){}var Ar={OPEN:"a",kb:"b",Ja:"c",wb:"d"};function Sc(){Ke.call(this,"d")}I(Sc,Ke);function Ac(){Ke.call(this,"c")}I(Ac,Ke);var Pi={},Mf=null;function kc(){return Mf=Mf||new ne}Pi.La="serverreachability";function Ff(a){Ke.call(this,Pi.La,a)}I(Ff,Ke);function kr(a){const d=kc();ae(d,new Ff(d))}Pi.STAT_EVENT="statevent";function Uf(a,d){Ke.call(this,Pi.STAT_EVENT,a),this.stat=d}I(Uf,Ke);function Ot(a){const d=kc();ae(d,new Uf(d,a))}Pi.Ma="timingevent";function $f(a,d){Ke.call(this,Pi.Ma,a),this.size=d}I($f,Ke);function Rr(a,d){if(typeof a!="function")throw Error("Fn must not be null and must be a function");return l.setTimeout(function(){a()},d)}function Pr(){this.g=!0}Pr.prototype.xa=function(){this.g=!1};function dT(a,d,p,_,O,U){a.info(function(){if(a.g)if(U)for(var z="",Ne=U.split("&"),pt=0;pt<Ne.length;pt++){var we=Ne[pt].split("=");if(1<we.length){var wt=we[0];we=we[1];var vt=wt.split("_");z=2<=vt.length&&vt[1]=="type"?z+(wt+"="+we+"&"):z+(wt+"=redacted&")}}else z=null;else z=U;return"XMLHTTP REQ ("+_+") [attempt "+O+"]: "+d+`
`+p+`
`+z})}function fT(a,d,p,_,O,U,z){a.info(function(){return"XMLHTTP RESP ("+_+") [ attempt "+O+"]: "+d+`
`+p+`
`+U+" "+z})}function Oi(a,d,p,_){a.info(function(){return"XMLHTTP TEXT ("+d+"): "+gT(a,p)+(_?" "+_:"")})}function pT(a,d){a.info(function(){return"TIMEOUT: "+d})}Pr.prototype.info=function(){};function gT(a,d){if(!a.g)return d;if(!d)return null;try{var p=JSON.parse(d);if(p){for(a=0;a<p.length;a++)if(Array.isArray(p[a])){var _=p[a];if(!(2>_.length)){var O=_[1];if(Array.isArray(O)&&!(1>O.length)){var U=O[0];if(U!="noop"&&U!="stop"&&U!="close")for(var z=1;z<O.length;z++)O[z]=""}}}}return ki(p)}catch{return d}}var Rc={NO_ERROR:0,gb:1,tb:2,sb:3,nb:4,rb:5,ub:6,Ia:7,TIMEOUT:8,xb:9},mT={lb:"complete",Hb:"success",Ja:"error",Ia:"abort",zb:"ready",Ab:"readystatechange",TIMEOUT:"timeout",vb:"incrementaldata",yb:"progress",ob:"downloadprogress",Pb:"uploadprogress"},Pc;function sa(){}I(sa,Ri),sa.prototype.g=function(){return new XMLHttpRequest},sa.prototype.i=function(){return{}},Pc=new sa;function fs(a,d,p,_){this.j=a,this.i=d,this.l=p,this.R=_||1,this.U=new ft(this),this.I=45e3,this.H=null,this.o=!1,this.m=this.A=this.v=this.L=this.F=this.S=this.B=null,this.D=[],this.g=null,this.C=0,this.s=this.u=null,this.X=-1,this.J=!1,this.O=0,this.M=null,this.W=this.K=this.T=this.P=!1,this.h=new Hf}function Hf(){this.i=null,this.g="",this.h=!1}var Bf={},Oc={};function Nc(a,d,p){a.L=1,a.v=aa(Hn(d)),a.m=p,a.P=!0,jf(a,null)}function jf(a,d){a.F=Date.now(),ia(a),a.A=Hn(a.v);var p=a.A,_=a.R;Array.isArray(_)||(_=[String(_)]),np(p.i,"t",_),a.C=0,p=a.j.J,a.h=new Hf,a.g=vp(a.j,p?d:null,!a.m),0<a.O&&(a.M=new rt(g(a.Y,a,a.g),a.O)),d=a.U,p=a.g,_=a.ca;var O="readystatechange";Array.isArray(O)||(O&&(yt[0]=O.toString()),O=yt);for(var U=0;U<O.length;U++){var z=j(p,O[U],_||d.handleEvent,!1,d.h||d);if(!z)break;d.g[z.key]=z}d=a.H?w(a.H):{},a.m?(a.u||(a.u="POST"),d["Content-Type"]="application/x-www-form-urlencoded",a.g.ea(a.A,a.u,a.m,d)):(a.u="GET",a.g.ea(a.A,a.u,null,d)),kr(),dT(a.i,a.u,a.A,a.l,a.R,a.m)}fs.prototype.ca=function(a){a=a.target;const d=this.M;d&&Bn(a)==3?d.j():this.Y(a)},fs.prototype.Y=function(a){try{if(a==this.g)e:{const vt=Bn(this.g);var d=this.g.Ba();const Di=this.g.Z();if(!(3>vt)&&(vt!=3||this.g&&(this.h.h||this.g.oa()||cp(this.g)))){this.J||vt!=4||d==7||(d==8||0>=Di?kr(3):kr(2)),xc(this);var p=this.g.Z();this.X=p;t:if(Vf(this)){var _=cp(this.g);a="";var O=_.length,U=Bn(this.g)==4;if(!this.h.i){if(typeof TextDecoder>"u"){Ws(this),Or(this);var z="";break t}this.h.i=new l.TextDecoder}for(d=0;d<O;d++)this.h.h=!0,a+=this.h.i.decode(_[d],{stream:!(U&&d==O-1)});_.length=0,this.h.g+=a,this.C=0,z=this.h.g}else z=this.g.oa();if(this.o=p==200,fT(this.i,this.u,this.A,this.l,this.R,vt,p),this.o){if(this.T&&!this.K){t:{if(this.g){var Ne,pt=this.g;if((Ne=pt.g?pt.g.getResponseHeader("X-HTTP-Initial-Response"):null)&&!x(Ne)){var we=Ne;break t}}we=null}if(p=we)Oi(this.i,this.l,p,"Initial handshake response via X-HTTP-Initial-Response"),this.K=!0,Dc(this,p);else{this.o=!1,this.s=3,Ot(12),Ws(this),Or(this);break e}}if(this.P){p=!0;let pn;for(;!this.J&&this.C<z.length;)if(pn=_T(this,z),pn==Oc){vt==4&&(this.s=4,Ot(14),p=!1),Oi(this.i,this.l,null,"[Incomplete Response]");break}else if(pn==Bf){this.s=4,Ot(15),Oi(this.i,this.l,z,"[Invalid Chunk]"),p=!1;break}else Oi(this.i,this.l,pn,null),Dc(this,pn);if(Vf(this)&&this.C!=0&&(this.h.g=this.h.g.slice(this.C),this.C=0),vt!=4||z.length!=0||this.h.h||(this.s=1,Ot(16),p=!1),this.o=this.o&&p,!p)Oi(this.i,this.l,z,"[Invalid Chunked Response]"),Ws(this),Or(this);else if(0<z.length&&!this.W){this.W=!0;var wt=this.j;wt.g==this&&wt.ba&&!wt.M&&(wt.j.info("Great, no buffering proxy detected. Bytes received: "+z.length),Hc(wt),wt.M=!0,Ot(11))}}else Oi(this.i,this.l,z,null),Dc(this,z);vt==4&&Ws(this),this.o&&!this.J&&(vt==4?mp(this.j,this):(this.o=!1,ia(this)))}else DT(this.g),p==400&&0<z.indexOf("Unknown SID")?(this.s=3,Ot(12)):(this.s=0,Ot(13)),Ws(this),Or(this)}}}catch{}finally{}};function Vf(a){return a.g?a.u=="GET"&&a.L!=2&&a.j.Ca:!1}function _T(a,d){var p=a.C,_=d.indexOf(`
`,p);return _==-1?Oc:(p=Number(d.substring(p,_)),isNaN(p)?Bf:(_+=1,_+p>d.length?Oc:(d=d.slice(_,_+p),a.C=_+p,d)))}fs.prototype.cancel=function(){this.J=!0,Ws(this)};function ia(a){a.S=Date.now()+a.I,Wf(a,a.I)}function Wf(a,d){if(a.B!=null)throw Error("WatchDog timer not null");a.B=Rr(g(a.ba,a),d)}function xc(a){a.B&&(l.clearTimeout(a.B),a.B=null)}fs.prototype.ba=function(){this.B=null;const a=Date.now();0<=a-this.S?(pT(this.i,this.A),this.L!=2&&(kr(),Ot(17)),Ws(this),this.s=2,Or(this)):Wf(this,this.S-a)};function Or(a){a.j.G==0||a.J||mp(a.j,a)}function Ws(a){xc(a);var d=a.M;d&&typeof d.ma=="function"&&d.ma(),a.M=null,ds(a.U),a.g&&(d=a.g,a.g=null,d.abort(),d.ma())}function Dc(a,d){try{var p=a.j;if(p.G!=0&&(p.g==a||Lc(p.h,a))){if(!a.K&&Lc(p.h,a)&&p.G==3){try{var _=p.Da.g.parse(d)}catch{_=null}if(Array.isArray(_)&&_.length==3){var O=_;if(O[0]==0){e:if(!p.u){if(p.g)if(p.g.F+3e3<a.F)fa(p),ha(p);else break e;$c(p),Ot(18)}}else p.za=O[1],0<p.za-p.T&&37500>O[2]&&p.F&&p.v==0&&!p.C&&(p.C=Rr(g(p.Za,p),6e3));if(1>=zf(p.h)&&p.ca){try{p.ca()}catch{}p.ca=void 0}}else qs(p,11)}else if((a.K||p.g==a)&&fa(p),!x(d))for(O=p.Da.g.parse(d),d=0;d<O.length;d++){let we=O[d];if(p.T=we[0],we=we[1],p.G==2)if(we[0]=="c"){p.K=we[1],p.ia=we[2];const wt=we[3];wt!=null&&(p.la=wt,p.j.info("VER="+p.la));const vt=we[4];vt!=null&&(p.Aa=vt,p.j.info("SVER="+p.Aa));const Di=we[5];Di!=null&&typeof Di=="number"&&0<Di&&(_=1.5*Di,p.L=_,p.j.info("backChannelRequestTimeoutMs_="+_)),_=p;const pn=a.g;if(pn){const pa=pn.g?pn.g.getResponseHeader("X-Client-Wire-Protocol"):null;if(pa){var U=_.h;U.g||pa.indexOf("spdy")==-1&&pa.indexOf("quic")==-1&&pa.indexOf("h2")==-1||(U.j=U.l,U.g=new Set,U.h&&(Mc(U,U.h),U.h=null))}if(_.D){const Bc=pn.g?pn.g.getResponseHeader("X-HTTP-Session-Id"):null;Bc&&(_.ya=Bc,Fe(_.I,_.D,Bc))}}p.G=3,p.l&&p.l.ua(),p.ba&&(p.R=Date.now()-a.F,p.j.info("Handshake RTT: "+p.R+"ms")),_=p;var z=a;if(_.qa=wp(_,_.J?_.ia:null,_.W),z.K){Gf(_.h,z);var Ne=z,pt=_.L;pt&&(Ne.I=pt),Ne.B&&(xc(Ne),ia(Ne)),_.g=z}else pp(_);0<p.i.length&&da(p)}else we[0]!="stop"&&we[0]!="close"||qs(p,7);else p.G==3&&(we[0]=="stop"||we[0]=="close"?we[0]=="stop"?qs(p,7):Uc(p):we[0]!="noop"&&p.l&&p.l.ta(we),p.v=0)}}kr(4)}catch{}}var yT=class{constructor(a,d){this.g=a,this.map=d}};function Kf(a){this.l=a||10,l.PerformanceNavigationTiming?(a=l.performance.getEntriesByType("navigation"),a=0<a.length&&(a[0].nextHopProtocol=="hq"||a[0].nextHopProtocol=="h2")):a=!!(l.chrome&&l.chrome.loadTimes&&l.chrome.loadTimes()&&l.chrome.loadTimes().wasFetchedViaSpdy),this.j=a?this.l:1,this.g=null,1<this.j&&(this.g=new Set),this.h=null,this.i=[]}function qf(a){return a.h?!0:a.g?a.g.size>=a.j:!1}function zf(a){return a.h?1:a.g?a.g.size:0}function Lc(a,d){return a.h?a.h==d:a.g?a.g.has(d):!1}function Mc(a,d){a.g?a.g.add(d):a.h=d}function Gf(a,d){a.h&&a.h==d?a.h=null:a.g&&a.g.has(d)&&a.g.delete(d)}Kf.prototype.cancel=function(){if(this.i=Yf(this),this.h)this.h.cancel(),this.h=null;else if(this.g&&this.g.size!==0){for(const a of this.g.values())a.cancel();this.g.clear()}};function Yf(a){if(a.h!=null)return a.i.concat(a.h.D);if(a.g!=null&&a.g.size!==0){let d=a.i;for(const p of a.g.values())d=d.concat(p.D);return d}return P(a.i)}function wT(a){if(a.V&&typeof a.V=="function")return a.V();if(typeof Map<"u"&&a instanceof Map||typeof Set<"u"&&a instanceof Set)return Array.from(a.values());if(typeof a=="string")return a.split("");if(c(a)){for(var d=[],p=a.length,_=0;_<p;_++)d.push(a[_]);return d}d=[],p=0;for(_ in a)d[p++]=a[_];return d}function vT(a){if(a.na&&typeof a.na=="function")return a.na();if(!a.V||typeof a.V!="function"){if(typeof Map<"u"&&a instanceof Map)return Array.from(a.keys());if(!(typeof Set<"u"&&a instanceof Set)){if(c(a)||typeof a=="string"){var d=[];a=a.length;for(var p=0;p<a;p++)d.push(p);return d}d=[],p=0;for(const _ in a)d[p++]=_;return d}}}function Xf(a,d){if(a.forEach&&typeof a.forEach=="function")a.forEach(d,void 0);else if(c(a)||typeof a=="string")Array.prototype.forEach.call(a,d,void 0);else for(var p=vT(a),_=wT(a),O=_.length,U=0;U<O;U++)d.call(void 0,_[U],p&&p[U],a)}var Jf=RegExp("^(?:([^:/?#.]+):)?(?://(?:([^\\\\/?#]*)@)?([^\\\\/?#]*?)(?::([0-9]+))?(?=[\\\\/?#]|$))?([^?#]+)?(?:\\?([^#]*))?(?:#([\\s\\S]*))?$");function bT(a,d){if(a){a=a.split("&");for(var p=0;p<a.length;p++){var _=a[p].indexOf("="),O=null;if(0<=_){var U=a[p].substring(0,_);O=a[p].substring(_+1)}else U=a[p];d(U,O?decodeURIComponent(O.replace(/\+/g," ")):"")}}}function Ks(a){if(this.g=this.o=this.j="",this.s=null,this.m=this.l="",this.h=!1,a instanceof Ks){this.h=a.h,ra(this,a.j),this.o=a.o,this.g=a.g,oa(this,a.s),this.l=a.l;var d=a.i,p=new Dr;p.i=d.i,d.g&&(p.g=new Map(d.g),p.h=d.h),Qf(this,p),this.m=a.m}else a&&(d=String(a).match(Jf))?(this.h=!1,ra(this,d[1]||"",!0),this.o=Nr(d[2]||""),this.g=Nr(d[3]||"",!0),oa(this,d[4]),this.l=Nr(d[5]||"",!0),Qf(this,d[6]||"",!0),this.m=Nr(d[7]||"")):(this.h=!1,this.i=new Dr(null,this.h))}Ks.prototype.toString=function(){var a=[],d=this.j;d&&a.push(xr(d,Zf,!0),":");var p=this.g;return(p||d=="file")&&(a.push("//"),(d=this.o)&&a.push(xr(d,Zf,!0),"@"),a.push(encodeURIComponent(String(p)).replace(/%25([0-9a-fA-F]{2})/g,"%$1")),p=this.s,p!=null&&a.push(":",String(p))),(p=this.l)&&(this.g&&p.charAt(0)!="/"&&a.push("/"),a.push(xr(p,p.charAt(0)=="/"?CT:TT,!0))),(p=this.i.toString())&&a.push("?",p),(p=this.m)&&a.push("#",xr(p,ST)),a.join("")};function Hn(a){return new Ks(a)}function ra(a,d,p){a.j=p?Nr(d,!0):d,a.j&&(a.j=a.j.replace(/:$/,""))}function oa(a,d){if(d){if(d=Number(d),isNaN(d)||0>d)throw Error("Bad port number "+d);a.s=d}else a.s=null}function Qf(a,d,p){d instanceof Dr?(a.i=d,AT(a.i,a.h)):(p||(d=xr(d,IT)),a.i=new Dr(d,a.h))}function Fe(a,d,p){a.i.set(d,p)}function aa(a){return Fe(a,"zx",Math.floor(2147483648*Math.random()).toString(36)+Math.abs(Math.floor(2147483648*Math.random())^Date.now()).toString(36)),a}function Nr(a,d){return a?d?decodeURI(a.replace(/%25/g,"%2525")):decodeURIComponent(a):""}function xr(a,d,p){return typeof a=="string"?(a=encodeURI(a).replace(d,ET),p&&(a=a.replace(/%25([0-9a-fA-F]{2})/g,"%$1")),a):null}function ET(a){return a=a.charCodeAt(0),"%"+(a>>4&15).toString(16)+(a&15).toString(16)}var Zf=/[#\/\?@]/g,TT=/[#\?:]/g,CT=/[#\?]/g,IT=/[#\?@]/g,ST=/#/g;function Dr(a,d){this.h=this.g=null,this.i=a||null,this.j=!!d}function ps(a){a.g||(a.g=new Map,a.h=0,a.i&&bT(a.i,function(d,p){a.add(decodeURIComponent(d.replace(/\+/g," ")),p)}))}t=Dr.prototype,t.add=function(a,d){ps(this),this.i=null,a=Ni(this,a);var p=this.g.get(a);return p||this.g.set(a,p=[]),p.push(d),this.h+=1,this};function ep(a,d){ps(a),d=Ni(a,d),a.g.has(d)&&(a.i=null,a.h-=a.g.get(d).length,a.g.delete(d))}function tp(a,d){return ps(a),d=Ni(a,d),a.g.has(d)}t.forEach=function(a,d){ps(this),this.g.forEach(function(p,_){p.forEach(function(O){a.call(d,O,_,this)},this)},this)},t.na=function(){ps(this);const a=Array.from(this.g.values()),d=Array.from(this.g.keys()),p=[];for(let _=0;_<d.length;_++){const O=a[_];for(let U=0;U<O.length;U++)p.push(d[_])}return p},t.V=function(a){ps(this);let d=[];if(typeof a=="string")tp(this,a)&&(d=d.concat(this.g.get(Ni(this,a))));else{a=Array.from(this.g.values());for(let p=0;p<a.length;p++)d=d.concat(a[p])}return d},t.set=function(a,d){return ps(this),this.i=null,a=Ni(this,a),tp(this,a)&&(this.h-=this.g.get(a).length),this.g.set(a,[d]),this.h+=1,this},t.get=function(a,d){return a?(a=this.V(a),0<a.length?String(a[0]):d):d};function np(a,d,p){ep(a,d),0<p.length&&(a.i=null,a.g.set(Ni(a,d),P(p)),a.h+=p.length)}t.toString=function(){if(this.i)return this.i;if(!this.g)return"";const a=[],d=Array.from(this.g.keys());for(var p=0;p<d.length;p++){var _=d[p];const U=encodeURIComponent(String(_)),z=this.V(_);for(_=0;_<z.length;_++){var O=U;z[_]!==""&&(O+="="+encodeURIComponent(String(z[_]))),a.push(O)}}return this.i=a.join("&")};function Ni(a,d){return d=String(d),a.j&&(d=d.toLowerCase()),d}function AT(a,d){d&&!a.j&&(ps(a),a.i=null,a.g.forEach(function(p,_){var O=_.toLowerCase();_!=O&&(ep(this,_),np(this,O,p))},a)),a.j=d}function kT(a,d){const p=new Pr;if(l.Image){const _=new Image;_.onload=m(gs,p,"TestLoadImage: loaded",!0,d,_),_.onerror=m(gs,p,"TestLoadImage: error",!1,d,_),_.onabort=m(gs,p,"TestLoadImage: abort",!1,d,_),_.ontimeout=m(gs,p,"TestLoadImage: timeout",!1,d,_),l.setTimeout(function(){_.ontimeout&&_.ontimeout()},1e4),_.src=a}else d(!1)}function RT(a,d){const p=new Pr,_=new AbortController,O=setTimeout(()=>{_.abort(),gs(p,"TestPingServer: timeout",!1,d)},1e4);fetch(a,{signal:_.signal}).then(U=>{clearTimeout(O),U.ok?gs(p,"TestPingServer: ok",!0,d):gs(p,"TestPingServer: server error",!1,d)}).catch(()=>{clearTimeout(O),gs(p,"TestPingServer: error",!1,d)})}function gs(a,d,p,_,O){try{O&&(O.onload=null,O.onerror=null,O.onabort=null,O.ontimeout=null),_(p)}catch{}}function PT(){this.g=new nn}function OT(a,d,p){const _=p||"";try{Xf(a,function(O,U){let z=O;u(O)&&(z=ki(O)),d.push(_+U+"="+encodeURIComponent(z))})}catch(O){throw d.push(_+"type="+encodeURIComponent("_badmap")),O}}function la(a){this.l=a.Ub||null,this.j=a.eb||!1}I(la,Ri),la.prototype.g=function(){return new ca(this.l,this.j)},la.prototype.i=function(a){return function(){return a}}({});function ca(a,d){ne.call(this),this.D=a,this.o=d,this.m=void 0,this.status=this.readyState=0,this.responseType=this.responseText=this.response=this.statusText="",this.onreadystatechange=null,this.u=new Headers,this.h=null,this.B="GET",this.A="",this.g=!1,this.v=this.j=this.l=null}I(ca,ne),t=ca.prototype,t.open=function(a,d){if(this.readyState!=0)throw this.abort(),Error("Error reopening a connection");this.B=a,this.A=d,this.readyState=1,Mr(this)},t.send=function(a){if(this.readyState!=1)throw this.abort(),Error("need to call open() first. ");this.g=!0;const d={headers:this.u,method:this.B,credentials:this.m,cache:void 0};a&&(d.body=a),(this.D||l).fetch(new Request(this.A,d)).then(this.Sa.bind(this),this.ga.bind(this))},t.abort=function(){this.response=this.responseText="",this.u=new Headers,this.status=0,this.j&&this.j.cancel("Request was aborted.").catch(()=>{}),1<=this.readyState&&this.g&&this.readyState!=4&&(this.g=!1,Lr(this)),this.readyState=0},t.Sa=function(a){if(this.g&&(this.l=a,this.h||(this.status=this.l.status,this.statusText=this.l.statusText,this.h=a.headers,this.readyState=2,Mr(this)),this.g&&(this.readyState=3,Mr(this),this.g)))if(this.responseType==="arraybuffer")a.arrayBuffer().then(this.Qa.bind(this),this.ga.bind(this));else if(typeof l.ReadableStream<"u"&&"body"in a){if(this.j=a.body.getReader(),this.o){if(this.responseType)throw Error('responseType must be empty for "streamBinaryChunks" mode responses.');this.response=[]}else this.response=this.responseText="",this.v=new TextDecoder;sp(this)}else a.text().then(this.Ra.bind(this),this.ga.bind(this))};function sp(a){a.j.read().then(a.Pa.bind(a)).catch(a.ga.bind(a))}t.Pa=function(a){if(this.g){if(this.o&&a.value)this.response.push(a.value);else if(!this.o){var d=a.value?a.value:new Uint8Array(0);(d=this.v.decode(d,{stream:!a.done}))&&(this.response=this.responseText+=d)}a.done?Lr(this):Mr(this),this.readyState==3&&sp(this)}},t.Ra=function(a){this.g&&(this.response=this.responseText=a,Lr(this))},t.Qa=function(a){this.g&&(this.response=a,Lr(this))},t.ga=function(){this.g&&Lr(this)};function Lr(a){a.readyState=4,a.l=null,a.j=null,a.v=null,Mr(a)}t.setRequestHeader=function(a,d){this.u.append(a,d)},t.getResponseHeader=function(a){return this.h&&this.h.get(a.toLowerCase())||""},t.getAllResponseHeaders=function(){if(!this.h)return"";const a=[],d=this.h.entries();for(var p=d.next();!p.done;)p=p.value,a.push(p[0]+": "+p[1]),p=d.next();return a.join(`\r
`)};function Mr(a){a.onreadystatechange&&a.onreadystatechange.call(a)}Object.defineProperty(ca.prototype,"withCredentials",{get:function(){return this.m==="include"},set:function(a){this.m=a?"include":"same-origin"}});function ip(a){let d="";return F(a,function(p,_){d+=_,d+=":",d+=p,d+=`\r
`}),d}function Fc(a,d,p){e:{for(_ in p){var _=!1;break e}_=!0}_||(p=ip(p),typeof a=="string"?p!=null&&encodeURIComponent(String(p)):Fe(a,d,p))}function Ye(a){ne.call(this),this.headers=new Map,this.o=a||null,this.h=!1,this.v=this.g=null,this.D="",this.m=0,this.l="",this.j=this.B=this.u=this.A=!1,this.I=null,this.H="",this.J=!1}I(Ye,ne);var NT=/^https?$/i,xT=["POST","PUT"];t=Ye.prototype,t.Ha=function(a){this.J=a},t.ea=function(a,d,p,_){if(this.g)throw Error("[goog.net.XhrIo] Object is active with another request="+this.D+"; newUri="+a);d=d?d.toUpperCase():"GET",this.D=a,this.l="",this.m=0,this.A=!1,this.h=!0,this.g=this.o?this.o.g():Pc.g(),this.v=this.o?Lf(this.o):Lf(Pc),this.g.onreadystatechange=g(this.Ea,this);try{this.B=!0,this.g.open(d,String(a),!0),this.B=!1}catch(U){rp(this,U);return}if(a=p||"",p=new Map(this.headers),_)if(Object.getPrototypeOf(_)===Object.prototype)for(var O in _)p.set(O,_[O]);else if(typeof _.keys=="function"&&typeof _.get=="function")for(const U of _.keys())p.set(U,_.get(U));else throw Error("Unknown input type for opt_headers: "+String(_));_=Array.from(p.keys()).find(U=>U.toLowerCase()=="content-type"),O=l.FormData&&a instanceof l.FormData,!(0<=Array.prototype.indexOf.call(xT,d,void 0))||_||O||p.set("Content-Type","application/x-www-form-urlencoded;charset=utf-8");for(const[U,z]of p)this.g.setRequestHeader(U,z);this.H&&(this.g.responseType=this.H),"withCredentials"in this.g&&this.g.withCredentials!==this.J&&(this.g.withCredentials=this.J);try{lp(this),this.u=!0,this.g.send(a),this.u=!1}catch(U){rp(this,U)}};function rp(a,d){a.h=!1,a.g&&(a.j=!0,a.g.abort(),a.j=!1),a.l=d,a.m=5,op(a),ua(a)}function op(a){a.A||(a.A=!0,ae(a,"complete"),ae(a,"error"))}t.abort=function(a){this.g&&this.h&&(this.h=!1,this.j=!0,this.g.abort(),this.j=!1,this.m=a||7,ae(this,"complete"),ae(this,"abort"),ua(this))},t.N=function(){this.g&&(this.h&&(this.h=!1,this.j=!0,this.g.abort(),this.j=!1),ua(this,!0)),Ye.aa.N.call(this)},t.Ea=function(){this.s||(this.B||this.u||this.j?ap(this):this.bb())},t.bb=function(){ap(this)};function ap(a){if(a.h&&typeof o<"u"&&(!a.v[1]||Bn(a)!=4||a.Z()!=2)){if(a.u&&Bn(a)==4)ye(a.Ea,0,a);else if(ae(a,"readystatechange"),Bn(a)==4){a.h=!1;try{const z=a.Z();e:switch(z){case 200:case 201:case 202:case 204:case 206:case 304:case 1223:var d=!0;break e;default:d=!1}var p;if(!(p=d)){var _;if(_=z===0){var O=String(a.D).match(Jf)[1]||null;!O&&l.self&&l.self.location&&(O=l.self.location.protocol.slice(0,-1)),_=!NT.test(O?O.toLowerCase():"")}p=_}if(p)ae(a,"complete"),ae(a,"success");else{a.m=6;try{var U=2<Bn(a)?a.g.statusText:""}catch{U=""}a.l=U+" ["+a.Z()+"]",op(a)}}finally{ua(a)}}}}function ua(a,d){if(a.g){lp(a);const p=a.g,_=a.v[0]?()=>{}:null;a.g=null,a.v=null,d||ae(a,"ready");try{p.onreadystatechange=_}catch{}}}function lp(a){a.I&&(l.clearTimeout(a.I),a.I=null)}t.isActive=function(){return!!this.g};function Bn(a){return a.g?a.g.readyState:0}t.Z=function(){try{return 2<Bn(this)?this.g.status:-1}catch{return-1}},t.oa=function(){try{return this.g?this.g.responseText:""}catch{return""}},t.Oa=function(a){if(this.g){var d=this.g.responseText;return a&&d.indexOf(a)==0&&(d=d.substring(a.length)),Pt(d)}};function cp(a){try{if(!a.g)return null;if("response"in a.g)return a.g.response;switch(a.H){case"":case"text":return a.g.responseText;case"arraybuffer":if("mozResponseArrayBuffer"in a.g)return a.g.mozResponseArrayBuffer}return null}catch{return null}}function DT(a){const d={};a=(a.g&&2<=Bn(a)&&a.g.getAllResponseHeaders()||"").split(`\r
`);for(let _=0;_<a.length;_++){if(x(a[_]))continue;var p=A(a[_]);const O=p[0];if(p=p[1],typeof p!="string")continue;p=p.trim();const U=d[O]||[];d[O]=U,U.push(p)}T(d,function(_){return _.join(", ")})}t.Ba=function(){return this.m},t.Ka=function(){return typeof this.l=="string"?this.l:String(this.l)};function Fr(a,d,p){return p&&p.internalChannelParams&&p.internalChannelParams[a]||d}function up(a){this.Aa=0,this.i=[],this.j=new Pr,this.ia=this.qa=this.I=this.W=this.g=this.ya=this.D=this.H=this.m=this.S=this.o=null,this.Ya=this.U=0,this.Va=Fr("failFast",!1,a),this.F=this.C=this.u=this.s=this.l=null,this.X=!0,this.za=this.T=-1,this.Y=this.v=this.B=0,this.Ta=Fr("baseRetryDelayMs",5e3,a),this.cb=Fr("retryDelaySeedMs",1e4,a),this.Wa=Fr("forwardChannelMaxRetries",2,a),this.wa=Fr("forwardChannelRequestTimeoutMs",2e4,a),this.pa=a&&a.xmlHttpFactory||void 0,this.Xa=a&&a.Tb||void 0,this.Ca=a&&a.useFetchStreams||!1,this.L=void 0,this.J=a&&a.supportsCrossDomainXhr||!1,this.K="",this.h=new Kf(a&&a.concurrentRequestLimit),this.Da=new PT,this.P=a&&a.fastHandshake||!1,this.O=a&&a.encodeInitMessageHeaders||!1,this.P&&this.O&&(this.O=!1),this.Ua=a&&a.Rb||!1,a&&a.xa&&this.j.xa(),a&&a.forceLongPolling&&(this.X=!1),this.ba=!this.P&&this.X&&a&&a.detectBufferingProxy||!1,this.ja=void 0,a&&a.longPollingTimeout&&0<a.longPollingTimeout&&(this.ja=a.longPollingTimeout),this.ca=void 0,this.R=0,this.M=!1,this.ka=this.A=null}t=up.prototype,t.la=8,t.G=1,t.connect=function(a,d,p,_){Ot(0),this.W=a,this.H=d||{},p&&_!==void 0&&(this.H.OSID=p,this.H.OAID=_),this.F=this.X,this.I=wp(this,null,this.W),da(this)};function Uc(a){if(hp(a),a.G==3){var d=a.U++,p=Hn(a.I);if(Fe(p,"SID",a.K),Fe(p,"RID",d),Fe(p,"TYPE","terminate"),Ur(a,p),d=new fs(a,a.j,d),d.L=2,d.v=aa(Hn(p)),p=!1,l.navigator&&l.navigator.sendBeacon)try{p=l.navigator.sendBeacon(d.v.toString(),"")}catch{}!p&&l.Image&&(new Image().src=d.v,p=!0),p||(d.g=vp(d.j,null),d.g.ea(d.v)),d.F=Date.now(),ia(d)}yp(a)}function ha(a){a.g&&(Hc(a),a.g.cancel(),a.g=null)}function hp(a){ha(a),a.u&&(l.clearTimeout(a.u),a.u=null),fa(a),a.h.cancel(),a.s&&(typeof a.s=="number"&&l.clearTimeout(a.s),a.s=null)}function da(a){if(!qf(a.h)&&!a.s){a.s=!0;var d=a.Ga;le||fn(),Z||(le(),Z=!0),Ve.add(d,a),a.B=0}}function LT(a,d){return zf(a.h)>=a.h.j-(a.s?1:0)?!1:a.s?(a.i=d.D.concat(a.i),!0):a.G==1||a.G==2||a.B>=(a.Va?0:a.Wa)?!1:(a.s=Rr(g(a.Ga,a,d),_p(a,a.B)),a.B++,!0)}t.Ga=function(a){if(this.s)if(this.s=null,this.G==1){if(!a){this.U=Math.floor(1e5*Math.random()),a=this.U++;const O=new fs(this,this.j,a);let U=this.o;if(this.S&&(U?(U=w(U),v(U,this.S)):U=this.S),this.m!==null||this.O||(O.H=U,U=null),this.P)e:{for(var d=0,p=0;p<this.i.length;p++){t:{var _=this.i[p];if("__data__"in _.map&&(_=_.map.__data__,typeof _=="string")){_=_.length;break t}_=void 0}if(_===void 0)break;if(d+=_,4096<d){d=p;break e}if(d===4096||p===this.i.length-1){d=p+1;break e}}d=1e3}else d=1e3;d=fp(this,O,d),p=Hn(this.I),Fe(p,"RID",a),Fe(p,"CVER",22),this.D&&Fe(p,"X-HTTP-Session-Id",this.D),Ur(this,p),U&&(this.O?d="headers="+encodeURIComponent(String(ip(U)))+"&"+d:this.m&&Fc(p,this.m,U)),Mc(this.h,O),this.Ua&&Fe(p,"TYPE","init"),this.P?(Fe(p,"$req",d),Fe(p,"SID","null"),O.T=!0,Nc(O,p,null)):Nc(O,p,d),this.G=2}}else this.G==3&&(a?dp(this,a):this.i.length==0||qf(this.h)||dp(this))};function dp(a,d){var p;d?p=d.l:p=a.U++;const _=Hn(a.I);Fe(_,"SID",a.K),Fe(_,"RID",p),Fe(_,"AID",a.T),Ur(a,_),a.m&&a.o&&Fc(_,a.m,a.o),p=new fs(a,a.j,p,a.B+1),a.m===null&&(p.H=a.o),d&&(a.i=d.D.concat(a.i)),d=fp(a,p,1e3),p.I=Math.round(.5*a.wa)+Math.round(.5*a.wa*Math.random()),Mc(a.h,p),Nc(p,_,d)}function Ur(a,d){a.H&&F(a.H,function(p,_){Fe(d,_,p)}),a.l&&Xf({},function(p,_){Fe(d,_,p)})}function fp(a,d,p){p=Math.min(a.i.length,p);var _=a.l?g(a.l.Na,a.l,a):null;e:{var O=a.i;let U=-1;for(;;){const z=["count="+p];U==-1?0<p?(U=O[0].g,z.push("ofs="+U)):U=0:z.push("ofs="+U);let Ne=!0;for(let pt=0;pt<p;pt++){let we=O[pt].g;const wt=O[pt].map;if(we-=U,0>we)U=Math.max(0,O[pt].g-100),Ne=!1;else try{OT(wt,z,"req"+we+"_")}catch{_&&_(wt)}}if(Ne){_=z.join("&");break e}}}return a=a.i.splice(0,p),d.D=a,_}function pp(a){if(!a.g&&!a.u){a.Y=1;var d=a.Fa;le||fn(),Z||(le(),Z=!0),Ve.add(d,a),a.v=0}}function $c(a){return a.g||a.u||3<=a.v?!1:(a.Y++,a.u=Rr(g(a.Fa,a),_p(a,a.v)),a.v++,!0)}t.Fa=function(){if(this.u=null,gp(this),this.ba&&!(this.M||this.g==null||0>=this.R)){var a=2*this.R;this.j.info("BP detection timer enabled: "+a),this.A=Rr(g(this.ab,this),a)}},t.ab=function(){this.A&&(this.A=null,this.j.info("BP detection timeout reached."),this.j.info("Buffering proxy detected and switch to long-polling!"),this.F=!1,this.M=!0,Ot(10),ha(this),gp(this))};function Hc(a){a.A!=null&&(l.clearTimeout(a.A),a.A=null)}function gp(a){a.g=new fs(a,a.j,"rpc",a.Y),a.m===null&&(a.g.H=a.o),a.g.O=0;var d=Hn(a.qa);Fe(d,"RID","rpc"),Fe(d,"SID",a.K),Fe(d,"AID",a.T),Fe(d,"CI",a.F?"0":"1"),!a.F&&a.ja&&Fe(d,"TO",a.ja),Fe(d,"TYPE","xmlhttp"),Ur(a,d),a.m&&a.o&&Fc(d,a.m,a.o),a.L&&(a.g.I=a.L);var p=a.g;a=a.ia,p.L=1,p.v=aa(Hn(d)),p.m=null,p.P=!0,jf(p,a)}t.Za=function(){this.C!=null&&(this.C=null,ha(this),$c(this),Ot(19))};function fa(a){a.C!=null&&(l.clearTimeout(a.C),a.C=null)}function mp(a,d){var p=null;if(a.g==d){fa(a),Hc(a),a.g=null;var _=2}else if(Lc(a.h,d))p=d.D,Gf(a.h,d),_=1;else return;if(a.G!=0){if(d.o)if(_==1){p=d.m?d.m.length:0,d=Date.now()-d.F;var O=a.B;_=kc(),ae(_,new $f(_,p)),da(a)}else pp(a);else if(O=d.s,O==3||O==0&&0<d.X||!(_==1&&LT(a,d)||_==2&&$c(a)))switch(p&&0<p.length&&(d=a.h,d.i=d.i.concat(p)),O){case 1:qs(a,5);break;case 4:qs(a,10);break;case 3:qs(a,6);break;default:qs(a,2)}}}function _p(a,d){let p=a.Ta+Math.floor(Math.random()*a.cb);return a.isActive()||(p*=2),p*d}function qs(a,d){if(a.j.info("Error code "+d),d==2){var p=g(a.fb,a),_=a.Xa;const O=!_;_=new Ks(_||"//www.google.com/images/cleardot.gif"),l.location&&l.location.protocol=="http"||ra(_,"https"),aa(_),O?kT(_.toString(),p):RT(_.toString(),p)}else Ot(2);a.G=0,a.l&&a.l.sa(d),yp(a),hp(a)}t.fb=function(a){a?(this.j.info("Successfully pinged google.com"),Ot(2)):(this.j.info("Failed to ping google.com"),Ot(1))};function yp(a){if(a.G=0,a.ka=[],a.l){const d=Yf(a.h);(d.length!=0||a.i.length!=0)&&(D(a.ka,d),D(a.ka,a.i),a.h.i.length=0,P(a.i),a.i.length=0),a.l.ra()}}function wp(a,d,p){var _=p instanceof Ks?Hn(p):new Ks(p);if(_.g!="")d&&(_.g=d+"."+_.g),oa(_,_.s);else{var O=l.location;_=O.protocol,d=d?d+"."+O.hostname:O.hostname,O=+O.port;var U=new Ks(null);_&&ra(U,_),d&&(U.g=d),O&&oa(U,O),p&&(U.l=p),_=U}return p=a.D,d=a.ya,p&&d&&Fe(_,p,d),Fe(_,"VER",a.la),Ur(a,_),_}function vp(a,d,p){if(d&&!a.J)throw Error("Can't create secondary domain capable XhrIo object.");return d=a.Ca&&!a.pa?new Ye(new la({eb:p})):new Ye(a.pa),d.Ha(a.J),d}t.isActive=function(){return!!this.l&&this.l.isActive(this)};function bp(){}t=bp.prototype,t.ua=function(){},t.ta=function(){},t.sa=function(){},t.ra=function(){},t.isActive=function(){return!0},t.Na=function(){};function sn(a,d){ne.call(this),this.g=new up(d),this.l=a,this.h=d&&d.messageUrlParams||null,a=d&&d.messageHeaders||null,d&&d.clientProtocolHeaderRequired&&(a?a["X-Client-Protocol"]="webchannel":a={"X-Client-Protocol":"webchannel"}),this.g.o=a,a=d&&d.initMessageHeaders||null,d&&d.messageContentType&&(a?a["X-WebChannel-Content-Type"]=d.messageContentType:a={"X-WebChannel-Content-Type":d.messageContentType}),d&&d.va&&(a?a["X-WebChannel-Client-Profile"]=d.va:a={"X-WebChannel-Client-Profile":d.va}),this.g.S=a,(a=d&&d.Sb)&&!x(a)&&(this.g.m=a),this.v=d&&d.supportsCrossDomainXhr||!1,this.u=d&&d.sendRawJson||!1,(d=d&&d.httpSessionIdParam)&&!x(d)&&(this.g.D=d,a=this.h,a!==null&&d in a&&(a=this.h,d in a&&delete a[d])),this.j=new xi(this)}I(sn,ne),sn.prototype.m=function(){this.g.l=this.j,this.v&&(this.g.J=!0),this.g.connect(this.l,this.h||void 0)},sn.prototype.close=function(){Uc(this.g)},sn.prototype.o=function(a){var d=this.g;if(typeof a=="string"){var p={};p.__data__=a,a=p}else this.u&&(p={},p.__data__=ki(a),a=p);d.i.push(new yT(d.Ya++,a)),d.G==3&&da(d)},sn.prototype.N=function(){this.g.l=null,delete this.j,Uc(this.g),delete this.g,sn.aa.N.call(this)};function Ep(a){Sc.call(this),a.__headers__&&(this.headers=a.__headers__,this.statusCode=a.__status__,delete a.__headers__,delete a.__status__);var d=a.__sm__;if(d){e:{for(const p in d){a=p;break e}a=void 0}(this.i=a)&&(a=this.i,d=d!==null&&a in d?d[a]:void 0),this.data=d}else this.data=a}I(Ep,Sc);function Tp(){Ac.call(this),this.status=1}I(Tp,Ac);function xi(a){this.g=a}I(xi,bp),xi.prototype.ua=function(){ae(this.g,"a")},xi.prototype.ta=function(a){ae(this.g,new Ep(a))},xi.prototype.sa=function(a){ae(this.g,new Tp)},xi.prototype.ra=function(){ae(this.g,"b")},sn.prototype.send=sn.prototype.o,sn.prototype.open=sn.prototype.m,sn.prototype.close=sn.prototype.close,Rc.NO_ERROR=0,Rc.TIMEOUT=8,Rc.HTTP_ERROR=6,mT.COMPLETE="complete",hT.EventType=Ar,Ar.OPEN="a",Ar.CLOSE="b",Ar.ERROR="c",Ar.MESSAGE="d",ne.prototype.listen=ne.prototype.K,Ye.prototype.listenOnce=Ye.prototype.L,Ye.prototype.getLastError=Ye.prototype.Ka,Ye.prototype.getLastErrorCode=Ye.prototype.Ba,Ye.prototype.getStatus=Ye.prototype.Z,Ye.prototype.getResponseJson=Ye.prototype.Oa,Ye.prototype.getResponseText=Ye.prototype.oa,Ye.prototype.send=Ye.prototype.ea,Ye.prototype.setWithCredentials=Ye.prototype.Ha}).apply(typeof ka<"u"?ka:typeof self<"u"?self:typeof window<"u"?window:{});const Og="@firebase/firestore";/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class $t{constructor(e){this.uid=e}isAuthenticated(){return this.uid!=null}toKey(){return this.isAuthenticated()?"uid:"+this.uid:"anonymous-user"}isEqual(e){return e.uid===this.uid}}$t.UNAUTHENTICATED=new $t(null),$t.GOOGLE_CREDENTIALS=new $t("google-credentials-uid"),$t.FIRST_PARTY=new $t("first-party-uid"),$t.MOCK_USER=new $t("mock-user");/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let ac="10.13.2";/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const pl=new Uo("@firebase/firestore");function Xt(t,...e){if(pl.logLevel<=Ie.DEBUG){const n=e.map(av);pl.debug(`Firestore (${ac}): ${t}`,...n)}}function Pd(t,...e){if(pl.logLevel<=Ie.ERROR){const n=e.map(av);pl.error(`Firestore (${ac}): ${t}`,...n)}}function av(t){if(typeof t=="string")return t;try{/**
* @license
* Copyright 2020 Google LLC
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*   http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/return function(n){return JSON.stringify(n)}(t)}catch{return t}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function lv(t="Unexpected state"){const e=`FIRESTORE (${ac}) INTERNAL ASSERTION FAILED: `+t;throw Pd(e),new Error(e)}function ph(t,e){t||lv()}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const nt={OK:"ok",CANCELLED:"cancelled",UNKNOWN:"unknown",INVALID_ARGUMENT:"invalid-argument",DEADLINE_EXCEEDED:"deadline-exceeded",NOT_FOUND:"not-found",ALREADY_EXISTS:"already-exists",PERMISSION_DENIED:"permission-denied",UNAUTHENTICATED:"unauthenticated",RESOURCE_EXHAUSTED:"resource-exhausted",FAILED_PRECONDITION:"failed-precondition",ABORTED:"aborted",OUT_OF_RANGE:"out-of-range",UNIMPLEMENTED:"unimplemented",INTERNAL:"internal",UNAVAILABLE:"unavailable",DATA_LOSS:"data-loss"};class st extends Sn{constructor(e,n){super(e,n),this.code=e,this.message=n,this.toString=()=>`${this.name}: [code=${this.code}]: ${this.message}`}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Zi{constructor(){this.promise=new Promise((e,n)=>{this.resolve=e,this.reject=n})}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class TR{constructor(e,n){this.user=n,this.type="OAuth",this.headers=new Map,this.headers.set("Authorization",`Bearer ${e}`)}}class CR{getToken(){return Promise.resolve(null)}invalidateToken(){}start(e,n){e.enqueueRetryable(()=>n($t.UNAUTHENTICATED))}shutdown(){}}class IR{constructor(e){this.t=e,this.currentUser=$t.UNAUTHENTICATED,this.i=0,this.forceRefresh=!1,this.auth=null}start(e,n){let s=this.i;const i=c=>this.i!==s?(s=this.i,n(c)):Promise.resolve();let r=new Zi;this.o=()=>{this.i++,this.currentUser=this.u(),r.resolve(),r=new Zi,e.enqueueRetryable(()=>i(this.currentUser))};const o=()=>{const c=r;e.enqueueRetryable(async()=>{await c.promise,await i(this.currentUser)})},l=c=>{Xt("FirebaseAuthCredentialsProvider","Auth detected"),this.auth=c,this.auth.addAuthTokenListener(this.o),o()};this.t.onInit(c=>l(c)),setTimeout(()=>{if(!this.auth){const c=this.t.getImmediate({optional:!0});c?l(c):(Xt("FirebaseAuthCredentialsProvider","Auth not yet detected"),r.resolve(),r=new Zi)}},0),o()}getToken(){const e=this.i,n=this.forceRefresh;return this.forceRefresh=!1,this.auth?this.auth.getToken(n).then(s=>this.i!==e?(Xt("FirebaseAuthCredentialsProvider","getToken aborted due to token change."),this.getToken()):s?(ph(typeof s.accessToken=="string"),new TR(s.accessToken,this.currentUser)):null):Promise.resolve(null)}invalidateToken(){this.forceRefresh=!0}shutdown(){this.auth&&this.auth.removeAuthTokenListener(this.o)}u(){const e=this.auth&&this.auth.getUid();return ph(e===null||typeof e=="string"),new $t(e)}}class SR{constructor(e,n,s){this.l=e,this.h=n,this.P=s,this.type="FirstParty",this.user=$t.FIRST_PARTY,this.I=new Map}T(){return this.P?this.P():null}get headers(){this.I.set("X-Goog-AuthUser",this.l);const e=this.T();return e&&this.I.set("Authorization",e),this.h&&this.I.set("X-Goog-Iam-Authorization-Token",this.h),this.I}}class AR{constructor(e,n,s){this.l=e,this.h=n,this.P=s}getToken(){return Promise.resolve(new SR(this.l,this.h,this.P))}start(e,n){e.enqueueRetryable(()=>n($t.FIRST_PARTY))}shutdown(){}invalidateToken(){}}class kR{constructor(e){this.value=e,this.type="AppCheck",this.headers=new Map,e&&e.length>0&&this.headers.set("x-firebase-appcheck",this.value)}}class RR{constructor(e){this.A=e,this.forceRefresh=!1,this.appCheck=null,this.R=null}start(e,n){const s=r=>{r.error!=null&&Xt("FirebaseAppCheckTokenProvider",`Error getting App Check token; using placeholder token instead. Error: ${r.error.message}`);const o=r.token!==this.R;return this.R=r.token,Xt("FirebaseAppCheckTokenProvider",`Received ${o?"new":"existing"} token.`),o?n(r.token):Promise.resolve()};this.o=r=>{e.enqueueRetryable(()=>s(r))};const i=r=>{Xt("FirebaseAppCheckTokenProvider","AppCheck detected"),this.appCheck=r,this.appCheck.addTokenListener(this.o)};this.A.onInit(r=>i(r)),setTimeout(()=>{if(!this.appCheck){const r=this.A.getImmediate({optional:!0});r?i(r):Xt("FirebaseAppCheckTokenProvider","AppCheck not yet detected")}},0)}getToken(){const e=this.forceRefresh;return this.forceRefresh=!1,this.appCheck?this.appCheck.getToken(e).then(n=>n?(ph(typeof n.token=="string"),this.R=n.token,new kR(n.token)):null):Promise.resolve(null)}invalidateToken(){this.forceRefresh=!0}shutdown(){this.appCheck&&this.appCheck.removeTokenListener(this.o)}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function PR(t){const e=typeof self<"u"&&(self.crypto||self.msCrypto),n=new Uint8Array(t);if(e&&typeof e.getRandomValues=="function")e.getRandomValues(n);else for(let s=0;s<t;s++)n[s]=Math.floor(256*Math.random());return n}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class OR{static newId(){const e="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",n=Math.floor(256/e.length)*e.length;let s="";for(;s.length<20;){const i=PR(40);for(let r=0;r<i.length;++r)s.length<20&&i[r]<n&&(s+=e.charAt(i[r]%e.length))}return s}}function gl(t,e){return t<e?-1:t>e?1:0}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class no{constructor(e,n){if(this.seconds=e,this.nanoseconds=n,n<0)throw new st(nt.INVALID_ARGUMENT,"Timestamp nanoseconds out of range: "+n);if(n>=1e9)throw new st(nt.INVALID_ARGUMENT,"Timestamp nanoseconds out of range: "+n);if(e<-62135596800)throw new st(nt.INVALID_ARGUMENT,"Timestamp seconds out of range: "+e);if(e>=253402300800)throw new st(nt.INVALID_ARGUMENT,"Timestamp seconds out of range: "+e)}static now(){return no.fromMillis(Date.now())}static fromDate(e){return no.fromMillis(e.getTime())}static fromMillis(e){const n=Math.floor(e/1e3),s=Math.floor(1e6*(e-1e3*n));return new no(n,s)}toDate(){return new Date(this.toMillis())}toMillis(){return 1e3*this.seconds+this.nanoseconds/1e6}_compareTo(e){return this.seconds===e.seconds?gl(this.nanoseconds,e.nanoseconds):gl(this.seconds,e.seconds)}isEqual(e){return e.seconds===this.seconds&&e.nanoseconds===this.nanoseconds}toString(){return"Timestamp(seconds="+this.seconds+", nanoseconds="+this.nanoseconds+")"}toJSON(){return{seconds:this.seconds,nanoseconds:this.nanoseconds}}valueOf(){const e=this.seconds- -62135596800;return String(e).padStart(12,"0")+"."+String(this.nanoseconds).padStart(9,"0")}}function cv(t){return t.name==="IndexedDbTransactionError"}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class NR{constructor(e,n,s,i,r,o,l,c,u){this.databaseId=e,this.appId=n,this.persistenceKey=s,this.host=i,this.ssl=r,this.forceLongPolling=o,this.autoDetectLongPolling=l,this.longPollingOptions=c,this.useFetchStreams=u}}class ml{constructor(e,n){this.projectId=e,this.database=n||"(default)"}static empty(){return new ml("","")}get isDefaultDatabase(){return this.database==="(default)"}isEqual(e){return e instanceof ml&&e.projectId===this.projectId&&e.database===this.database}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Ng,ge;(ge=Ng||(Ng={}))[ge.OK=0]="OK",ge[ge.CANCELLED=1]="CANCELLED",ge[ge.UNKNOWN=2]="UNKNOWN",ge[ge.INVALID_ARGUMENT=3]="INVALID_ARGUMENT",ge[ge.DEADLINE_EXCEEDED=4]="DEADLINE_EXCEEDED",ge[ge.NOT_FOUND=5]="NOT_FOUND",ge[ge.ALREADY_EXISTS=6]="ALREADY_EXISTS",ge[ge.PERMISSION_DENIED=7]="PERMISSION_DENIED",ge[ge.UNAUTHENTICATED=16]="UNAUTHENTICATED",ge[ge.RESOURCE_EXHAUSTED=8]="RESOURCE_EXHAUSTED",ge[ge.FAILED_PRECONDITION=9]="FAILED_PRECONDITION",ge[ge.ABORTED=10]="ABORTED",ge[ge.OUT_OF_RANGE=11]="OUT_OF_RANGE",ge[ge.UNIMPLEMENTED=12]="UNIMPLEMENTED",ge[ge.INTERNAL=13]="INTERNAL",ge[ge.UNAVAILABLE=14]="UNAVAILABLE",ge[ge.DATA_LOSS=15]="DATA_LOSS";/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */new ov([4294967295,4294967295],0);function du(){return typeof document<"u"?document:null}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class xR{constructor(e,n,s=1e3,i=1.5,r=6e4){this.ui=e,this.timerId=n,this.ko=s,this.qo=i,this.Qo=r,this.Ko=0,this.$o=null,this.Uo=Date.now(),this.reset()}reset(){this.Ko=0}Wo(){this.Ko=this.Qo}Go(e){this.cancel();const n=Math.floor(this.Ko+this.zo()),s=Math.max(0,Date.now()-this.Uo),i=Math.max(0,n-s);i>0&&Xt("ExponentialBackoff",`Backing off for ${i} ms (base delay: ${this.Ko} ms, delay with jitter: ${n} ms, last attempt: ${s} ms ago)`),this.$o=this.ui.enqueueAfterDelay(this.timerId,i,()=>(this.Uo=Date.now(),e())),this.Ko*=this.qo,this.Ko<this.ko&&(this.Ko=this.ko),this.Ko>this.Qo&&(this.Ko=this.Qo)}jo(){this.$o!==null&&(this.$o.skipDelay(),this.$o=null)}cancel(){this.$o!==null&&(this.$o.cancel(),this.$o=null)}zo(){return(Math.random()-.5)*this.Ko}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Od{constructor(e,n,s,i,r){this.asyncQueue=e,this.timerId=n,this.targetTimeMs=s,this.op=i,this.removalCallback=r,this.deferred=new Zi,this.then=this.deferred.promise.then.bind(this.deferred.promise),this.deferred.promise.catch(o=>{})}get promise(){return this.deferred.promise}static createAndSchedule(e,n,s,i,r){const o=Date.now()+s,l=new Od(e,n,o,i,r);return l.start(s),l}start(e){this.timerHandle=setTimeout(()=>this.handleDelayElapsed(),e)}skipDelay(){return this.handleDelayElapsed()}cancel(e){this.timerHandle!==null&&(this.clearTimeout(),this.deferred.reject(new st(nt.CANCELLED,"Operation cancelled"+(e?": "+e:""))))}handleDelayElapsed(){this.asyncQueue.enqueueAndForget(()=>this.timerHandle!==null?(this.clearTimeout(),this.op().then(e=>this.deferred.resolve(e))):Promise.resolve())}clearTimeout(){this.timerHandle!==null&&(this.removalCallback(this),clearTimeout(this.timerHandle),this.timerHandle=null)}}function DR(t,e){if(Pd("AsyncQueue",`${e}: ${t}`),cv(t))return new st(nt.UNAVAILABLE,`${e}: ${t}`);throw t}var xg,Dg;(Dg=xg||(xg={})).ea="default",Dg.Cache="cache";/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class LR{constructor(e,n,s,i){this.authCredentials=e,this.appCheckCredentials=n,this.asyncQueue=s,this.databaseInfo=i,this.user=$t.UNAUTHENTICATED,this.clientId=OR.newId(),this.authCredentialListener=()=>Promise.resolve(),this.appCheckCredentialListener=()=>Promise.resolve(),this.authCredentials.start(s,async r=>{Xt("FirestoreClient","Received user=",r.uid),await this.authCredentialListener(r),this.user=r}),this.appCheckCredentials.start(s,r=>(Xt("FirestoreClient","Received new app check token=",r),this.appCheckCredentialListener(r,this.user)))}get configuration(){return{asyncQueue:this.asyncQueue,databaseInfo:this.databaseInfo,clientId:this.clientId,authCredentials:this.authCredentials,appCheckCredentials:this.appCheckCredentials,initialUser:this.user,maxConcurrentLimboResolutions:100}}setCredentialChangeListener(e){this.authCredentialListener=e}setAppCheckTokenChangeListener(e){this.appCheckCredentialListener=e}verifyNotTerminated(){if(this.asyncQueue.isShuttingDown)throw new st(nt.FAILED_PRECONDITION,"The client has already been terminated.")}terminate(){this.asyncQueue.enterRestrictedMode();const e=new Zi;return this.asyncQueue.enqueueAndForgetEvenWhileRestricted(async()=>{try{this._onlineComponents&&await this._onlineComponents.terminate(),this._offlineComponents&&await this._offlineComponents.terminate(),this.authCredentials.shutdown(),this.appCheckCredentials.shutdown(),e.resolve()}catch(n){const s=DR(n,"Failed to shutdown persistence");e.reject(s)}}),e.promise}}/**
 * @license
 * Copyright 2023 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function uv(t){const e={};return t.timeoutSeconds!==void 0&&(e.timeoutSeconds=t.timeoutSeconds),e}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Lg=new Map;function MR(t,e,n,s){if(e===!0&&s===!0)throw new st(nt.INVALID_ARGUMENT,`${t} and ${n} cannot be used together.`)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Mg{constructor(e){var n,s;if(e.host===void 0){if(e.ssl!==void 0)throw new st(nt.INVALID_ARGUMENT,"Can't provide ssl option if host option is not set");this.host="firestore.googleapis.com",this.ssl=!0}else this.host=e.host,this.ssl=(n=e.ssl)===null||n===void 0||n;if(this.credentials=e.credentials,this.ignoreUndefinedProperties=!!e.ignoreUndefinedProperties,this.localCache=e.localCache,e.cacheSizeBytes===void 0)this.cacheSizeBytes=41943040;else{if(e.cacheSizeBytes!==-1&&e.cacheSizeBytes<1048576)throw new st(nt.INVALID_ARGUMENT,"cacheSizeBytes must be at least 1048576");this.cacheSizeBytes=e.cacheSizeBytes}MR("experimentalForceLongPolling",e.experimentalForceLongPolling,"experimentalAutoDetectLongPolling",e.experimentalAutoDetectLongPolling),this.experimentalForceLongPolling=!!e.experimentalForceLongPolling,this.experimentalForceLongPolling?this.experimentalAutoDetectLongPolling=!1:e.experimentalAutoDetectLongPolling===void 0?this.experimentalAutoDetectLongPolling=!0:this.experimentalAutoDetectLongPolling=!!e.experimentalAutoDetectLongPolling,this.experimentalLongPollingOptions=uv((s=e.experimentalLongPollingOptions)!==null&&s!==void 0?s:{}),function(r){if(r.timeoutSeconds!==void 0){if(isNaN(r.timeoutSeconds))throw new st(nt.INVALID_ARGUMENT,`invalid long polling timeout: ${r.timeoutSeconds} (must not be NaN)`);if(r.timeoutSeconds<5)throw new st(nt.INVALID_ARGUMENT,`invalid long polling timeout: ${r.timeoutSeconds} (minimum allowed value is 5)`);if(r.timeoutSeconds>30)throw new st(nt.INVALID_ARGUMENT,`invalid long polling timeout: ${r.timeoutSeconds} (maximum allowed value is 30)`)}}(this.experimentalLongPollingOptions),this.useFetchStreams=!!e.useFetchStreams}isEqual(e){return this.host===e.host&&this.ssl===e.ssl&&this.credentials===e.credentials&&this.cacheSizeBytes===e.cacheSizeBytes&&this.experimentalForceLongPolling===e.experimentalForceLongPolling&&this.experimentalAutoDetectLongPolling===e.experimentalAutoDetectLongPolling&&function(s,i){return s.timeoutSeconds===i.timeoutSeconds}(this.experimentalLongPollingOptions,e.experimentalLongPollingOptions)&&this.ignoreUndefinedProperties===e.ignoreUndefinedProperties&&this.useFetchStreams===e.useFetchStreams}}class FR{constructor(e,n,s,i){this._authCredentials=e,this._appCheckCredentials=n,this._databaseId=s,this._app=i,this.type="firestore-lite",this._persistenceKey="(lite)",this._settings=new Mg({}),this._settingsFrozen=!1}get app(){if(!this._app)throw new st(nt.FAILED_PRECONDITION,"Firestore was not initialized using the Firebase SDK. 'app' is not available");return this._app}get _initialized(){return this._settingsFrozen}get _terminated(){return this._terminateTask!==void 0}_setSettings(e){if(this._settingsFrozen)throw new st(nt.FAILED_PRECONDITION,"Firestore has already been started and its settings can no longer be changed. You can only modify settings before calling any other methods on a Firestore object.");this._settings=new Mg(e),e.credentials!==void 0&&(this._authCredentials=function(s){if(!s)return new CR;switch(s.type){case"firstParty":return new AR(s.sessionIndex||"0",s.iamToken||null,s.authTokenFactory||null);case"provider":return s.client;default:throw new st(nt.INVALID_ARGUMENT,"makeAuthCredentialsProvider failed due to invalid credential type")}}(e.credentials))}_getSettings(){return this._settings}_freezeSettings(){return this._settingsFrozen=!0,this._settings}_delete(){return this._terminateTask||(this._terminateTask=this._terminate()),this._terminateTask}toJSON(){return{app:this._app,databaseId:this._databaseId,settings:this._settings}}_terminate(){return function(n){const s=Lg.get(n);s&&(Xt("ComponentProvider","Removing Datastore"),Lg.delete(n),s.terminate())}(this),Promise.resolve()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class UR{constructor(){this.au=Promise.resolve(),this.uu=[],this.cu=!1,this.lu=[],this.hu=null,this.Pu=!1,this.Iu=!1,this.Tu=[],this.t_=new xR(this,"async_queue_retry"),this.Eu=()=>{const n=du();n&&Xt("AsyncQueue","Visibility state changed to "+n.visibilityState),this.t_.jo()};const e=du();e&&typeof e.addEventListener=="function"&&e.addEventListener("visibilitychange",this.Eu)}get isShuttingDown(){return this.cu}enqueueAndForget(e){this.enqueue(e)}enqueueAndForgetEvenWhileRestricted(e){this.du(),this.Au(e)}enterRestrictedMode(e){if(!this.cu){this.cu=!0,this.Iu=e||!1;const n=du();n&&typeof n.removeEventListener=="function"&&n.removeEventListener("visibilitychange",this.Eu)}}enqueue(e){if(this.du(),this.cu)return new Promise(()=>{});const n=new Zi;return this.Au(()=>this.cu&&this.Iu?Promise.resolve():(e().then(n.resolve,n.reject),n.promise)).then(()=>n.promise)}enqueueRetryable(e){this.enqueueAndForget(()=>(this.uu.push(e),this.Ru()))}async Ru(){if(this.uu.length!==0){try{await this.uu[0](),this.uu.shift(),this.t_.reset()}catch(e){if(!cv(e))throw e;Xt("AsyncQueue","Operation failed with retryable error: "+e)}this.uu.length>0&&this.t_.Go(()=>this.Ru())}}Au(e){const n=this.au.then(()=>(this.Pu=!0,e().catch(s=>{this.hu=s,this.Pu=!1;const i=function(o){let l=o.message||"";return o.stack&&(l=o.stack.includes(o.message)?o.stack:o.message+`
`+o.stack),l}(s);throw Pd("INTERNAL UNHANDLED ERROR: ",i),s}).then(s=>(this.Pu=!1,s))));return this.au=n,n}enqueueAfterDelay(e,n,s){this.du(),this.Tu.indexOf(e)>-1&&(n=0);const i=Od.createAndSchedule(this,e,n,s,r=>this.Vu(r));return this.lu.push(i),i}du(){this.hu&&lv()}verifyOperationInProgress(){}async mu(){let e;do e=this.au,await e;while(e!==this.au)}fu(e){for(const n of this.lu)if(n.timerId===e)return!0;return!1}gu(e){return this.mu().then(()=>{this.lu.sort((n,s)=>n.targetTimeMs-s.targetTimeMs);for(const n of this.lu)if(n.skipDelay(),e!=="all"&&n.timerId===e)break;return this.mu()})}pu(e){this.Tu.push(e)}Vu(e){const n=this.lu.indexOf(e);this.lu.splice(n,1)}}class $R extends FR{constructor(e,n,s,i){super(e,n,s,i),this.type="firestore",this._queue=function(){return new UR}(),this._persistenceKey=(i==null?void 0:i.name)||"[DEFAULT]"}_terminate(){return this._firestoreClient||HR(this),this._firestoreClient.terminate()}}function HR(t){var e,n,s;const i=t._freezeSettings(),r=function(l,c,u,h){return new NR(l,c,u,h.host,h.ssl,h.experimentalForceLongPolling,h.experimentalAutoDetectLongPolling,uv(h.experimentalLongPollingOptions),h.useFetchStreams)}(t._databaseId,((e=t._app)===null||e===void 0?void 0:e.options.appId)||"",t._persistenceKey,i);t._firestoreClient=new LR(t._authCredentials,t._appCheckCredentials,t._queue,r),!((n=i.localCache)===null||n===void 0)&&n._offlineComponentProvider&&(!((s=i.localCache)===null||s===void 0)&&s._onlineComponentProvider)&&(t._firestoreClient._uninitializedComponentsProvider={_offlineKind:i.localCache.kind,_offline:i.localCache._offlineComponentProvider,_online:i.localCache._onlineComponentProvider})}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class BR{constructor(e,n){if(!isFinite(e)||e<-90||e>90)throw new st(nt.INVALID_ARGUMENT,"Latitude must be a number between -90 and 90, but was: "+e);if(!isFinite(n)||n<-180||n>180)throw new st(nt.INVALID_ARGUMENT,"Longitude must be a number between -180 and 180, but was: "+n);this._lat=e,this._long=n}get latitude(){return this._lat}get longitude(){return this._long}isEqual(e){return this._lat===e._lat&&this._long===e._long}toJSON(){return{latitude:this._lat,longitude:this._long}}_compareTo(e){return gl(this._lat,e._lat)||gl(this._long,e._long)}}(function(e,n=!0){(function(i){ac=i})(js),Kt(new Mt("firestore",(s,{instanceIdentifier:i,options:r})=>{const o=s.getProvider("app").getImmediate(),l=new $R(new IR(s.getProvider("auth-internal")),new RR(s.getProvider("app-check-internal")),function(u,h){if(!Object.prototype.hasOwnProperty.apply(u.options,["projectId"]))throw new st(nt.INVALID_ARGUMENT,'"projectId" not provided in firebase.initializeApp.');return new ml(u.options.projectId,h)}(o,i),o);return r=Object.assign({useFetchStreams:n},r),l._setSettings(r),l},"PUBLIC").setMultipleInstances(!0)),mt(Og,"4.7.2",e),mt(Og,"4.7.2","esm2017")})();const jR=-1,VR=-2,WR=-3,KR=-4,qR=-5,zR=-6;function GR(t,e){return YR(JSON.parse(t),e)}function YR(t,e){if(typeof t=="number")return i(t,!0);if(!Array.isArray(t)||t.length===0)throw new Error("Invalid input");const n=t,s=Array(n.length);function i(r,o=!1){if(r===jR)return;if(r===WR)return NaN;if(r===KR)return 1/0;if(r===qR)return-1/0;if(r===zR)return-0;if(o)throw new Error("Invalid input");if(r in s)return s[r];const l=n[r];if(!l||typeof l!="object")s[r]=l;else if(Array.isArray(l))if(typeof l[0]=="string"){const c=l[0],u=e==null?void 0:e[c];if(u)return s[r]=u(i(l[1]));switch(c){case"Date":s[r]=new Date(l[1]);break;case"Set":const h=new Set;s[r]=h;for(let m=1;m<l.length;m+=1)h.add(i(l[m]));break;case"Map":const f=new Map;s[r]=f;for(let m=1;m<l.length;m+=2)f.set(i(l[m]),i(l[m+1]));break;case"RegExp":s[r]=new RegExp(l[1],l[2]);break;case"Object":s[r]=Object(l[1]);break;case"BigInt":s[r]=BigInt(l[1]);break;case"null":const g=Object.create(null);s[r]=g;for(let m=1;m<l.length;m+=2)g[l[m]]=i(l[m+1]);break;default:throw new Error(`Unknown type ${c}`)}}else{const c=new Array(l.length);s[r]=c;for(let u=0;u<l.length;u+=1){const h=l[u];h!==VR&&(c[u]=i(h))}}else{const c={};s[r]=c;for(const u in l){const h=l[u];c[u]=i(h)}}return s[r]}return i(0)}const XR=new Set(["title","titleTemplate","script","style","noscript"]),Ba=new Set(["base","meta","link","style","script","noscript"]),JR=new Set(["title","titleTemplate","templateParams","base","htmlAttrs","bodyAttrs","meta","link","style","script","noscript"]),QR=new Set(["base","title","titleTemplate","bodyAttrs","htmlAttrs","templateParams"]),hv=new Set(["tagPosition","tagPriority","tagDuplicateStrategy","children","innerHTML","textContent","processTemplateParams"]),ZR=typeof window<"u";function _l(t){let e=9;for(let n=0;n<t.length;)e=Math.imul(e^t.charCodeAt(n++),9**9);return((e^e>>>9)+65536).toString(16).substring(1,8).toLowerCase()}function gh(t){if(t._h)return t._h;if(t._d)return _l(t._d);let e=`${t.tag}:${t.textContent||t.innerHTML||""}:`;for(const n in t.props)e+=`${n}:${String(t.props[n])},`;return _l(e)}function eP(t,e){return t instanceof Promise?t.then(e):e(t)}function mh(t,e,n,s){const i=s||fv(typeof e=="object"&&typeof e!="function"&&!(e instanceof Promise)?{...e}:{[t==="script"||t==="noscript"||t==="style"?"innerHTML":"textContent"]:e},t==="templateParams"||t==="titleTemplate");if(i instanceof Promise)return i.then(o=>mh(t,e,n,o));const r={tag:t,props:i};for(const o of hv){const l=r.props[o]!==void 0?r.props[o]:n[o];l!==void 0&&((!(o==="innerHTML"||o==="textContent"||o==="children")||XR.has(r.tag))&&(r[o==="children"?"innerHTML":o]=l),delete r.props[o])}return r.props.body&&(r.tagPosition="bodyClose",delete r.props.body),r.tag==="script"&&typeof r.innerHTML=="object"&&(r.innerHTML=JSON.stringify(r.innerHTML),r.props.type=r.props.type||"application/json"),Array.isArray(r.props.content)?r.props.content.map(o=>({...r,props:{...r.props,content:o}})):r}function tP(t,e){var s;const n=t==="class"?" ":";";return e&&typeof e=="object"&&!Array.isArray(e)&&(e=Object.entries(e).filter(([,i])=>i).map(([i,r])=>t==="style"?`${i}:${r}`:i)),(s=String(Array.isArray(e)?e.join(n):e))==null?void 0:s.split(n).filter(i=>!!i.trim()).join(n)}function dv(t,e,n,s){for(let i=s;i<n.length;i+=1){const r=n[i];if(r==="class"||r==="style"){t[r]=tP(r,t[r]);continue}if(t[r]instanceof Promise)return t[r].then(o=>(t[r]=o,dv(t,e,n,i)));if(!e&&!hv.has(r)){const o=String(t[r]),l=r.startsWith("data-");o==="true"||o===""?t[r]=l?"true":!0:t[r]||(l&&o==="false"?t[r]="false":delete t[r])}}}function fv(t,e=!1){const n=dv(t,e,Object.keys(t),0);return n instanceof Promise?n.then(()=>t):t}const nP=10;function pv(t,e,n){for(let s=n;s<e.length;s+=1){const i=e[s];if(i instanceof Promise)return i.then(r=>(e[s]=r,pv(t,e,s)));Array.isArray(i)?t.push(...i):t.push(i)}}function sP(t){const e=[],n=t.resolvedInput;for(const i in n){if(!Object.prototype.hasOwnProperty.call(n,i))continue;const r=n[i];if(!(r===void 0||!JR.has(i))){if(Array.isArray(r)){for(const o of r)e.push(mh(i,o,t));continue}e.push(mh(i,r,t))}}if(e.length===0)return[];const s=[];return eP(pv(s,e,0),()=>s.map((i,r)=>(i._e=t._i,t.mode&&(i._m=t.mode),i._p=(t._i<<nP)+r,i)))}const Fg=new Set(["onload","onerror","onabort","onprogress","onloadstart"]),Ug={base:-10,title:10},$g={critical:-80,high:-10,low:20};function yl(t){const e=t.tagPriority;if(typeof e=="number")return e;let n=100;return t.tag==="meta"?t.props["http-equiv"]==="content-security-policy"?n=-30:t.props.charset?n=-20:t.props.name==="viewport"&&(n=-15):t.tag==="link"&&t.props.rel==="preconnect"?n=20:t.tag in Ug&&(n=Ug[t.tag]),e&&e in $g?n+$g[e]:n}const iP=[{prefix:"before:",offset:-1},{prefix:"after:",offset:1}],rP=["name","property","http-equiv"];function gv(t){const{props:e,tag:n}=t;if(QR.has(n))return n;if(n==="link"&&e.rel==="canonical")return"canonical";if(e.charset)return"charset";if(e.id)return`${n}:id:${e.id}`;for(const s of rP)if(e[s]!==void 0)return`${n}:${s}:${e[s]}`;return!1}const Ts="%separator";function oP(t,e){var s;let n;if(e==="s"||e==="pageTitle")n=t.pageTitle;else if(e.includes(".")){const i=e.indexOf(".");n=(s=t[e.substring(0,i)])==null?void 0:s[e.substring(i+1)]}else n=t[e];return n!==void 0?(n||"").replace(/"/g,'\\"'):void 0}const aP=new RegExp(`${Ts}(?:\\s*${Ts})*`,"g");function Ra(t,e,n){if(typeof t!="string"||!t.includes("%"))return t;let s=t;try{s=decodeURI(t)}catch{}const i=s.match(/%\w+(?:\.\w+)?/g);if(!i)return t;const r=t.includes(Ts);return t=t.replace(/%\w+(?:\.\w+)?/g,o=>{if(o===Ts||!i.includes(o))return o;const l=oP(e,o.slice(1));return l!==void 0?l:o}).trim(),r&&(t.endsWith(Ts)&&(t=t.slice(0,-Ts.length)),t.startsWith(Ts)&&(t=t.slice(Ts.length)),t=t.replace(aP,n).trim()),t}function Hg(t,e){return t==null?e||null:typeof t=="function"?t(e):t}async function mv(t,e={}){const n=e.document||t.resolvedOptions.document;if(!n||!t.dirty)return;const s={shouldRender:!0,tags:[]};if(await t.hooks.callHook("dom:beforeRender",s),!!s.shouldRender)return t._domUpdatePromise||(t._domUpdatePromise=new Promise(async i=>{var f;const r=(await t.resolveTags()).map(g=>({tag:g,id:Ba.has(g.tag)?gh(g):g.tag,shouldRender:!0}));let o=t._dom;if(!o){o={elMap:{htmlAttrs:n.documentElement,bodyAttrs:n.body}};const g=new Set;for(const m of["body","head"]){const I=(f=n[m])==null?void 0:f.children;for(const P of I){const D=P.tagName.toLowerCase();if(!Ba.has(D))continue;const M={tag:D,props:await fv(P.getAttributeNames().reduce((N,F)=>({...N,[F]:P.getAttribute(F)}),{})),innerHTML:P.innerHTML},x=gv(M);let b=x,R=1;for(;b&&g.has(b);)b=`${x}:${R++}`;b&&(M._d=b,g.add(b)),o.elMap[P.getAttribute("data-hid")||gh(M)]=P}}}o.pendingSideEffects={...o.sideEffects},o.sideEffects={};function l(g,m,I){const P=`${g}:${m}`;o.sideEffects[P]=I,delete o.pendingSideEffects[P]}function c({id:g,$el:m,tag:I}){const P=I.tag.endsWith("Attrs");if(o.elMap[g]=m,P||(I.textContent&&I.textContent!==m.textContent&&(m.textContent=I.textContent),I.innerHTML&&I.innerHTML!==m.innerHTML&&(m.innerHTML=I.innerHTML),l(g,"el",()=>{var D;(D=o.elMap[g])==null||D.remove(),delete o.elMap[g]})),I._eventHandlers)for(const D in I._eventHandlers)Object.prototype.hasOwnProperty.call(I._eventHandlers,D)&&m.getAttribute(`data-${D}`)!==""&&((I.tag==="bodyAttrs"?n.defaultView:m).addEventListener(D.substring(2),I._eventHandlers[D].bind(m)),m.setAttribute(`data-${D}`,""));for(const D in I.props){if(!Object.prototype.hasOwnProperty.call(I.props,D))continue;const M=I.props[D],x=`attr:${D}`;if(D==="class"){if(!M)continue;for(const b of M.split(" "))P&&l(g,`${x}:${b}`,()=>m.classList.remove(b)),!m.classList.contains(b)&&m.classList.add(b)}else if(D==="style"){if(!M)continue;for(const b of M.split(";")){const R=b.indexOf(":"),N=b.substring(0,R).trim(),F=b.substring(R+1).trim();l(g,`${x}:${N}`,()=>{m.style.removeProperty(N)}),m.style.setProperty(N,F)}}else m.getAttribute(D)!==M&&m.setAttribute(D,M===!0?"":String(M)),P&&l(g,x,()=>m.removeAttribute(D))}}const u=[],h={bodyClose:void 0,bodyOpen:void 0,head:void 0};for(const g of r){const{tag:m,shouldRender:I,id:P}=g;if(I){if(m.tag==="title"){n.title=m.textContent;continue}g.$el=g.$el||o.elMap[P],g.$el?c(g):Ba.has(m.tag)&&u.push(g)}}for(const g of u){const m=g.tag.tagPosition||"head";g.$el=n.createElement(g.tag.tag),c(g),h[m]=h[m]||n.createDocumentFragment(),h[m].appendChild(g.$el)}for(const g of r)await t.hooks.callHook("dom:renderTag",g,n,l);h.head&&n.head.appendChild(h.head),h.bodyOpen&&n.body.insertBefore(h.bodyOpen,n.body.firstChild),h.bodyClose&&n.body.appendChild(h.bodyClose);for(const g in o.pendingSideEffects)o.pendingSideEffects[g]();t._dom=o,await t.hooks.callHook("dom:rendered",{renders:r}),i()}).finally(()=>{t._domUpdatePromise=void 0,t.dirty=!1})),t._domUpdatePromise}function lP(t,e={}){const n=e.delayFn||(s=>setTimeout(s,10));return t._domDebouncedUpdatePromise=t._domDebouncedUpdatePromise||new Promise(s=>n(()=>mv(t,e).then(()=>{delete t._domDebouncedUpdatePromise,s()})))}function cP(t){return e=>{var s,i;const n=((i=(s=e.resolvedOptions.document)==null?void 0:s.head.querySelector('script[id="unhead:payload"]'))==null?void 0:i.innerHTML)||!1;return n&&e.push(JSON.parse(n)),{mode:"client",hooks:{"entries:updated":r=>{lP(r,t)}}}}}const uP=new Set(["templateParams","htmlAttrs","bodyAttrs"]),hP={hooks:{"tag:normalise":({tag:t})=>{t.props.hid&&(t.key=t.props.hid,delete t.props.hid),t.props.vmid&&(t.key=t.props.vmid,delete t.props.vmid),t.props.key&&(t.key=t.props.key,delete t.props.key);const e=gv(t);e&&!e.startsWith("meta:og:")&&!e.startsWith("meta:twitter:")&&delete t.key;const n=e||(t.key?`${t.tag}:${t.key}`:!1);n&&(t._d=n)},"tags:resolve":t=>{const e=Object.create(null);for(const s of t.tags){const i=(s.key?`${s.tag}:${s.key}`:s._d)||gh(s),r=e[i];if(r){let l=s==null?void 0:s.tagDuplicateStrategy;if(!l&&uP.has(s.tag)&&(l="merge"),l==="merge"){const c=r.props;c.style&&s.props.style&&(c.style[c.style.length-1]!==";"&&(c.style+=";"),s.props.style=`${c.style} ${s.props.style}`),c.class&&s.props.class?s.props.class=`${c.class} ${s.props.class}`:c.class&&(s.props.class=c.class),e[i].props={...c,...s.props};continue}else if(s._e===r._e){r._duped=r._duped||[],s._d=`${r._d}:${r._duped.length+1}`,r._duped.push(s);continue}else if(yl(s)>yl(r))continue}if(!(s.innerHTML||s.textContent||Object.keys(s.props).length!==0)&&Ba.has(s.tag)){delete e[i];continue}e[i]=s}const n=[];for(const s in e){const i=e[s],r=i._duped;n.push(i),r&&(delete i._duped,n.push(...r))}t.tags=n,t.tags=t.tags.filter(s=>!(s.tag==="meta"&&(s.props.name||s.props.property)&&!s.props.content))}}},dP=new Set(["script","link","bodyAttrs"]),fP=t=>({hooks:{"tags:resolve":e=>{for(const n of e.tags){if(!dP.has(n.tag))continue;const s=n.props;for(const i in s){if(i[0]!=="o"||i[1]!=="n"||!Object.prototype.hasOwnProperty.call(s,i))continue;const r=s[i];typeof r=="function"&&(t.ssr&&Fg.has(i)?s[i]=`this.dataset.${i}fired = true`:delete s[i],n._eventHandlers=n._eventHandlers||{},n._eventHandlers[i]=r)}t.ssr&&n._eventHandlers&&(n.props.src||n.props.href)&&(n.key=n.key||_l(n.props.src||n.props.href))}},"dom:renderTag":({$el:e,tag:n})=>{var i,r;const s=e==null?void 0:e.dataset;if(s)for(const o in s){if(!o.endsWith("fired"))continue;const l=o.slice(0,-5);Fg.has(l)&&((r=(i=n._eventHandlers)==null?void 0:i[l])==null||r.call(e,new Event(l.substring(2))))}}}}),pP=new Set(["link","style","script","noscript"]),gP={hooks:{"tag:normalise":({tag:t})=>{t.key&&pP.has(t.tag)&&(t.props["data-hid"]=t._h=_l(t.key))}}},mP={mode:"server",hooks:{"tags:beforeResolve":t=>{const e={};let n=!1;for(const s of t.tags)s._m!=="server"||s.tag!=="titleTemplate"&&s.tag!=="templateParams"&&s.tag!=="title"||(e[s.tag]=s.tag==="title"||s.tag==="titleTemplate"?s.textContent:s.props,n=!0);n&&t.tags.push({tag:"script",innerHTML:JSON.stringify(e),props:{id:"unhead:payload",type:"application/json"}})}}},_P={hooks:{"tags:resolve":t=>{var e;for(const n of t.tags)if(typeof n.tagPriority=="string")for(const{prefix:s,offset:i}of iP){if(!n.tagPriority.startsWith(s))continue;const r=n.tagPriority.substring(s.length),o=(e=t.tags.find(l=>l._d===r))==null?void 0:e._p;if(o!==void 0){n._p=o+i;break}}t.tags.sort((n,s)=>{const i=yl(n),r=yl(s);return i<r?-1:i>r?1:n._p-s._p})}}},yP={meta:"content",link:"href",htmlAttrs:"lang"},wP=["innerHTML","textContent"],vP=t=>({hooks:{"tags:resolve":e=>{var o;const{tags:n}=e;let s;for(let l=0;l<n.length;l+=1)n[l].tag==="templateParams"&&(s=e.tags.splice(l,1)[0].props,l-=1);const i=s||{},r=i.separator||"|";delete i.separator,i.pageTitle=Ra(i.pageTitle||((o=n.find(l=>l.tag==="title"))==null?void 0:o.textContent)||"",i,r);for(const l of n){if(l.processTemplateParams===!1)continue;const c=yP[l.tag];if(c&&typeof l.props[c]=="string")l.props[c]=Ra(l.props[c],i,r);else if(l.processTemplateParams||l.tag==="titleTemplate"||l.tag==="title")for(const u of wP)typeof l[u]=="string"&&(l[u]=Ra(l[u],i,r))}t._templateParams=i,t._separator=r},"tags:afterResolve":({tags:e})=>{let n;for(let s=0;s<e.length;s+=1){const i=e[s];i.tag==="title"&&i.processTemplateParams!==!1&&(n=i)}n!=null&&n.textContent&&(n.textContent=Ra(n.textContent,t._templateParams,t._separator))}}}),bP={hooks:{"tags:resolve":t=>{const{tags:e}=t;let n,s;for(let i=0;i<e.length;i+=1){const r=e[i];r.tag==="title"?n=r:r.tag==="titleTemplate"&&(s=r)}if(s&&n){const i=Hg(s.textContent,n.textContent);i!==null?n.textContent=i||n.textContent:t.tags.splice(t.tags.indexOf(n),1)}else if(s){const i=Hg(s.textContent);i!==null&&(s.textContent=i,s.tag="title",s=void 0)}s&&t.tags.splice(t.tags.indexOf(s),1)}}},EP={hooks:{"tags:afterResolve":t=>{for(const e of t.tags)typeof e.innerHTML=="string"&&(e.innerHTML&&(e.props.type==="application/ld+json"||e.props.type==="application/json")?e.innerHTML=e.innerHTML.replace(/</g,"\\u003C"):e.innerHTML=e.innerHTML.replace(new RegExp(`</${e.tag}`,"g"),`<\\/${e.tag}`))}}};let _v;function TP(t={}){const e=CP(t);return e.use(cP()),_v=e}function Bg(t,e){return!t||t==="server"&&e||t==="client"&&!e}function CP(t={}){const e=Lw();e.addHooks(t.hooks||{}),t.document=t.document||(ZR?document:void 0);const n=!t.document,s=()=>{l.dirty=!0,e.callHook("entries:updated",l)};let i=0,r=[];const o=[],l={plugins:o,dirty:!1,resolvedOptions:t,hooks:e,headEntries(){return r},use(c){const u=typeof c=="function"?c(l):c;(!u.key||!o.some(h=>h.key===u.key))&&(o.push(u),Bg(u.mode,n)&&e.addHooks(u.hooks||{}))},push(c,u){u==null||delete u.head;const h={_i:i++,input:c,...u};return Bg(h.mode,n)&&(r.push(h),s()),{dispose(){r=r.filter(f=>f._i!==h._i),s()},patch(f){for(const g of r)g._i===h._i&&(g.input=h.input=f);s()}}},async resolveTags(){const c={tags:[],entries:[...r]};await e.callHook("entries:resolve",c);for(const u of c.entries){const h=u.resolvedInput||u.input;if(u.resolvedInput=await(u.transform?u.transform(h):h),u.resolvedInput)for(const f of await sP(u)){const g={tag:f,entry:u,resolvedOptions:l.resolvedOptions};await e.callHook("tag:normalise",g),c.tags.push(g.tag)}}return await e.callHook("tags:beforeResolve",c),await e.callHook("tags:resolve",c),await e.callHook("tags:afterResolve",c),c.tags},ssr:n};return[hP,mP,fP,gP,_P,vP,bP,EP,...(t==null?void 0:t.plugins)||[]].forEach(c=>l.use(c)),l.hooks.callHook("init",l),l}function IP(){return _v}const SP=ww[0]==="3";function AP(t){return typeof t=="function"?t():Pe(t)}function wl(t){if(t instanceof Promise||t instanceof Date||t instanceof RegExp)return t;const e=AP(t);if(!t||!e)return e;if(Array.isArray(e))return e.map(n=>wl(n));if(typeof e=="object"){const n={};for(const s in e)if(Object.prototype.hasOwnProperty.call(e,s)){if(s==="titleTemplate"||s[0]==="o"&&s[1]==="n"){n[s]=Pe(e[s]);continue}n[s]=wl(e[s])}return n}return e}const kP={hooks:{"entries:resolve":t=>{for(const e of t.entries)e.resolvedInput=wl(e.input)}}},yv="usehead";function RP(t){return{install(n){SP&&(n.config.globalProperties.$unhead=t,n.config.globalProperties.$head=t,n.provide(yv,t))}}.install}function PP(t={}){t.domDelayFn=t.domDelayFn||(n=>bi(()=>setTimeout(()=>n(),0)));const e=TP(t);return e.use(kP),e.install=RP(e),e}const _h=typeof globalThis<"u"?globalThis:typeof window<"u"?window:typeof global<"u"?global:typeof self<"u"?self:{},yh="__unhead_injection_handler__";function OP(t){_h[yh]=t}function NP(){if(yh in _h)return _h[yh]();const t=ht(yv);return t||IP()}function wv(t,e={}){const n=e.head||NP();if(n)return n.ssr?n.push(t,e):xP(n,t,e)}function xP(t,e,n={}){const s=Ge(!1),i=Ge({});_I(()=>{i.value=s.value?{}:wl(e)});const r=t.push(i.value,n);return li(i,l=>{r.patch(l)}),Ei()&&(Ql(()=>{r.dispose()}),Ly(()=>{s.value=!0}),Dy(()=>{s.value=!1})),r}let ja,Va;function DP(){return ja=$fetch(Ed(`builds/meta/${Mo().app.buildId}.json`),{responseType:"json"}),ja.then(t=>{Va=FA(t.matcher)}).catch(t=>{console.error("[nuxt] Error fetching app manifest.",t)}),ja}function lc(){return ja||DP()}async function Nd(t){if(await lc(),!Va)return console.error("[nuxt] Error creating app manifest matcher.",Va),{};try{return Hw({},...Va.matchAll(t).reverse())}catch(e){return console.error("[nuxt] Error matching route rules.",e),{}}}async function jg(t,e={}){const n=await MP(t,e),s=Je(),i=s._payloadCache=s._payloadCache||{};return n in i||(i[n]=bv(t).then(r=>r?vv(n).then(o=>o||(delete i[n],null)):(i[n]=null,null))),i[n]}const LP="_payload.json";async function MP(t,e={}){const n=new URL(t,"http://localhost");if(n.host!=="localhost"||Ti(n.pathname,{acceptRelative:!0}))throw new Error("Payload URL must not include hostname: "+t);const s=Mo(),i=e.hash||(e.fresh?Date.now():s.app.buildId),r=s.app.cdnURL,o=r&&await bv(t)?r:s.app.baseURL;return bd(o,n.pathname,LP+(i?`?${i}`:""))}async function vv(t){const e=fetch(t).then(n=>n.text().then(Ev));try{return await e}catch(n){console.warn("[nuxt] Cannot load payload ",t,n)}return null}async function bv(t=Td().path){if(t=vd(t),(await lc()).prerendered.includes(t))return!0;const n=await Nd(t);return!!n.prerender&&!n.redirect}let Xs=null;async function FP(){var s;if(Xs)return Xs;const t=document.getElementById("__NUXT_DATA__");if(!t)return{};const e=await Ev(t.textContent||""),n=t.dataset.src?await vv(t.dataset.src):void 0;return Xs={...e,...n,...window.__NUXT__},(s=Xs.config)!=null&&s.public&&(Xs.config.public=In(Xs.config.public)),Xs}async function Ev(t){return await GR(t,Je()._payloadRevivers)}function Wa(t,e){Je()._payloadRevivers[t]=e}const UP=DA(()=>{Wa("FirebaseTimestamp",t=>Uu(new no(t.seconds,t.nanoseconds))),Wa("FirebaseGeoPoint",t=>Uu(new BR(t.latitude,t.longitude))),Wa("DocumentData",t=>{const e=typeof t=="string"?JSON.parse(t):t,n=e.id;return delete e.id,Object.defineProperty(e,"id",{value:n})})}),Vg={NuxtError:t=>sc(t),EmptyShallowRef:t=>go(t==="_"?void 0:t==="0n"?BigInt(0):rl(t)),EmptyRef:t=>Ge(t==="_"?void 0:t==="0n"?BigInt(0):rl(t)),ShallowRef:t=>go(t),ShallowReactive:t=>Gn(t),Ref:t=>Ge(t),Reactive:t=>In(t)},$P=Rt({name:"nuxt:revive-payload:client",order:-30,async setup(t){let e,n;for(const s in Vg)Wa(s,Vg[s]);Object.assign(t.payload,([e,n]=Qi(()=>t.runWithContext(FP)),e=await e,n(),e)),window.__NUXT__=t.payload}}),HP=[],BP=Rt({name:"nuxt:head",enforce:"pre",setup(t){const e=PP({plugins:HP});OP(()=>Je().vueApp._context.provides.usehead),t.vueApp.use(e);{let n=!0;const s=async()=>{n=!1,await mv(e)};e.hooks.hook("dom:beforeRender",i=>{i.shouldRender=!n}),t.hooks.hook("page:start",()=>{n=!0}),t.hooks.hook("page:finish",()=>{t.isHydrating||s()}),t.hooks.hook("app:error",s),t.hooks.hook("app:suspense:resolve",s)}}});/*!
  * vue-router v4.4.5
  * (c) 2024 Eduardo San Martin Morote
  * @license MIT
  */const Hi=typeof document<"u";function Tv(t){return typeof t=="object"||"displayName"in t||"props"in t||"__vccOpts"in t}function jP(t){return t.__esModule||t[Symbol.toStringTag]==="Module"||t.default&&Tv(t.default)}const Ae=Object.assign;function fu(t,e){const n={};for(const s in e){const i=e[s];n[s]=Tn(i)?i.map(t):t(i)}return n}const so=()=>{},Tn=Array.isArray,Cv=/#/g,VP=/&/g,WP=/\//g,KP=/=/g,qP=/\?/g,Iv=/\+/g,zP=/%5B/g,GP=/%5D/g,Sv=/%5E/g,YP=/%60/g,Av=/%7B/g,XP=/%7C/g,kv=/%7D/g,JP=/%20/g;function xd(t){return encodeURI(""+t).replace(XP,"|").replace(zP,"[").replace(GP,"]")}function QP(t){return xd(t).replace(Av,"{").replace(kv,"}").replace(Sv,"^")}function wh(t){return xd(t).replace(Iv,"%2B").replace(JP,"+").replace(Cv,"%23").replace(VP,"%26").replace(YP,"`").replace(Av,"{").replace(kv,"}").replace(Sv,"^")}function ZP(t){return wh(t).replace(KP,"%3D")}function e1(t){return xd(t).replace(Cv,"%23").replace(qP,"%3F")}function t1(t){return t==null?"":e1(t).replace(WP,"%2F")}function To(t){try{return decodeURIComponent(""+t)}catch{}return""+t}const n1=/\/$/,s1=t=>t.replace(n1,"");function pu(t,e,n="/"){let s,i={},r="",o="";const l=e.indexOf("#");let c=e.indexOf("?");return l<c&&l>=0&&(c=-1),c>-1&&(s=e.slice(0,c),r=e.slice(c+1,l>-1?l:e.length),i=t(r)),l>-1&&(s=s||e.slice(0,l),o=e.slice(l,e.length)),s=a1(s??e,n),{fullPath:s+(r&&"?")+r+o,path:s,query:i,hash:To(o)}}function i1(t,e){const n=e.query?t(e.query):"";return e.path+(n&&"?")+n+(e.hash||"")}function Wg(t,e){return!e||!t.toLowerCase().startsWith(e.toLowerCase())?t:t.slice(e.length)||"/"}function r1(t,e,n){const s=e.matched.length-1,i=n.matched.length-1;return s>-1&&s===i&&hr(e.matched[s],n.matched[i])&&Rv(e.params,n.params)&&t(e.query)===t(n.query)&&e.hash===n.hash}function hr(t,e){return(t.aliasOf||t)===(e.aliasOf||e)}function Rv(t,e){if(Object.keys(t).length!==Object.keys(e).length)return!1;for(const n in t)if(!o1(t[n],e[n]))return!1;return!0}function o1(t,e){return Tn(t)?Kg(t,e):Tn(e)?Kg(e,t):t===e}function Kg(t,e){return Tn(e)?t.length===e.length&&t.every((n,s)=>n===e[s]):t.length===1&&t[0]===e}function a1(t,e){if(t.startsWith("/"))return t;if(!t)return e;const n=e.split("/"),s=t.split("/"),i=s[s.length-1];(i===".."||i===".")&&s.push("");let r=n.length-1,o,l;for(o=0;o<s.length;o++)if(l=s[o],l!==".")if(l==="..")r>1&&r--;else break;return n.slice(0,r).join("/")+"/"+s.slice(o).join("/")}const gn={path:"/",name:void 0,params:{},query:{},hash:"",fullPath:"/",matched:[],meta:{},redirectedFrom:void 0};var Co;(function(t){t.pop="pop",t.push="push"})(Co||(Co={}));var io;(function(t){t.back="back",t.forward="forward",t.unknown=""})(io||(io={}));function l1(t){if(!t)if(Hi){const e=document.querySelector("base");t=e&&e.getAttribute("href")||"/",t=t.replace(/^\w+:\/\/[^\/]+/,"")}else t="/";return t[0]!=="/"&&t[0]!=="#"&&(t="/"+t),s1(t)}const c1=/^[^#]+#/;function u1(t,e){return t.replace(c1,"#")+e}function h1(t,e){const n=document.documentElement.getBoundingClientRect(),s=t.getBoundingClientRect();return{behavior:e.behavior,left:s.left-n.left-(e.left||0),top:s.top-n.top-(e.top||0)}}const cc=()=>({left:window.scrollX,top:window.scrollY});function d1(t){let e;if("el"in t){const n=t.el,s=typeof n=="string"&&n.startsWith("#"),i=typeof n=="string"?s?document.getElementById(n.slice(1)):document.querySelector(n):n;if(!i)return;e=h1(i,t)}else e=t;"scrollBehavior"in document.documentElement.style?window.scrollTo(e):window.scrollTo(e.left!=null?e.left:window.scrollX,e.top!=null?e.top:window.scrollY)}function qg(t,e){return(history.state?history.state.position-e:-1)+t}const vh=new Map;function f1(t,e){vh.set(t,e)}function p1(t){const e=vh.get(t);return vh.delete(t),e}let g1=()=>location.protocol+"//"+location.host;function Pv(t,e){const{pathname:n,search:s,hash:i}=e,r=t.indexOf("#");if(r>-1){let l=i.includes(t.slice(r))?t.slice(r).length:1,c=i.slice(l);return c[0]!=="/"&&(c="/"+c),Wg(c,"")}return Wg(n,t)+s+i}function m1(t,e,n,s){let i=[],r=[],o=null;const l=({state:g})=>{const m=Pv(t,location),I=n.value,P=e.value;let D=0;if(g){if(n.value=m,e.value=g,o&&o===I){o=null;return}D=P?g.position-P.position:0}else s(m);i.forEach(M=>{M(n.value,I,{delta:D,type:Co.pop,direction:D?D>0?io.forward:io.back:io.unknown})})};function c(){o=n.value}function u(g){i.push(g);const m=()=>{const I=i.indexOf(g);I>-1&&i.splice(I,1)};return r.push(m),m}function h(){const{history:g}=window;g.state&&g.replaceState(Ae({},g.state,{scroll:cc()}),"")}function f(){for(const g of r)g();r=[],window.removeEventListener("popstate",l),window.removeEventListener("beforeunload",h)}return window.addEventListener("popstate",l),window.addEventListener("beforeunload",h,{passive:!0}),{pauseListeners:c,listen:u,destroy:f}}function zg(t,e,n,s=!1,i=!1){return{back:t,current:e,forward:n,replaced:s,position:window.history.length,scroll:i?cc():null}}function _1(t){const{history:e,location:n}=window,s={value:Pv(t,n)},i={value:e.state};i.value||r(s.value,{back:null,current:s.value,forward:null,position:e.length-1,replaced:!0,scroll:null},!0);function r(c,u,h){const f=t.indexOf("#"),g=f>-1?(n.host&&document.querySelector("base")?t:t.slice(f))+c:g1()+t+c;try{e[h?"replaceState":"pushState"](u,"",g),i.value=u}catch(m){console.error(m),n[h?"replace":"assign"](g)}}function o(c,u){const h=Ae({},e.state,zg(i.value.back,c,i.value.forward,!0),u,{position:i.value.position});r(c,h,!0),s.value=c}function l(c,u){const h=Ae({},i.value,e.state,{forward:c,scroll:cc()});r(h.current,h,!0);const f=Ae({},zg(s.value,c,null),{position:h.position+1},u);r(c,f,!1),s.value=c}return{location:s,state:i,push:l,replace:o}}function Ov(t){t=l1(t);const e=_1(t),n=m1(t,e.state,e.location,e.replace);function s(r,o=!0){o||n.pauseListeners(),history.go(r)}const i=Ae({location:"",base:t,go:s,createHref:u1.bind(null,t)},e,n);return Object.defineProperty(i,"location",{enumerable:!0,get:()=>e.location.value}),Object.defineProperty(i,"state",{enumerable:!0,get:()=>e.state.value}),i}function y1(t){return t=location.host?t||location.pathname+location.search:"",t.includes("#")||(t+="#"),Ov(t)}function w1(t){return typeof t=="string"||t&&typeof t=="object"}function Nv(t){return typeof t=="string"||typeof t=="symbol"}const xv=Symbol("");var Gg;(function(t){t[t.aborted=4]="aborted",t[t.cancelled=8]="cancelled",t[t.duplicated=16]="duplicated"})(Gg||(Gg={}));function dr(t,e){return Ae(new Error,{type:t,[xv]:!0},e)}function Vn(t,e){return t instanceof Error&&xv in t&&(e==null||!!(t.type&e))}const Yg="[^/]+?",v1={sensitive:!1,strict:!1,start:!0,end:!0},b1=/[.+*?^${}()[\]/\\]/g;function E1(t,e){const n=Ae({},v1,e),s=[];let i=n.start?"^":"";const r=[];for(const u of t){const h=u.length?[]:[90];n.strict&&!u.length&&(i+="/");for(let f=0;f<u.length;f++){const g=u[f];let m=40+(n.sensitive?.25:0);if(g.type===0)f||(i+="/"),i+=g.value.replace(b1,"\\$&"),m+=40;else if(g.type===1){const{value:I,repeatable:P,optional:D,regexp:M}=g;r.push({name:I,repeatable:P,optional:D});const x=M||Yg;if(x!==Yg){m+=10;try{new RegExp(`(${x})`)}catch(R){throw new Error(`Invalid custom RegExp for param "${I}" (${x}): `+R.message)}}let b=P?`((?:${x})(?:/(?:${x}))*)`:`(${x})`;f||(b=D&&u.length<2?`(?:/${b})`:"/"+b),D&&(b+="?"),i+=b,m+=20,D&&(m+=-8),P&&(m+=-20),x===".*"&&(m+=-50)}h.push(m)}s.push(h)}if(n.strict&&n.end){const u=s.length-1;s[u][s[u].length-1]+=.7000000000000001}n.strict||(i+="/?"),n.end?i+="$":n.strict&&(i+="(?:/|$)");const o=new RegExp(i,n.sensitive?"":"i");function l(u){const h=u.match(o),f={};if(!h)return null;for(let g=1;g<h.length;g++){const m=h[g]||"",I=r[g-1];f[I.name]=m&&I.repeatable?m.split("/"):m}return f}function c(u){let h="",f=!1;for(const g of t){(!f||!h.endsWith("/"))&&(h+="/"),f=!1;for(const m of g)if(m.type===0)h+=m.value;else if(m.type===1){const{value:I,repeatable:P,optional:D}=m,M=I in u?u[I]:"";if(Tn(M)&&!P)throw new Error(`Provided param "${I}" is an array but it is not repeatable (* or + modifiers)`);const x=Tn(M)?M.join("/"):M;if(!x)if(D)g.length<2&&(h.endsWith("/")?h=h.slice(0,-1):f=!0);else throw new Error(`Missing required param "${I}"`);h+=x}}return h||"/"}return{re:o,score:s,keys:r,parse:l,stringify:c}}function T1(t,e){let n=0;for(;n<t.length&&n<e.length;){const s=e[n]-t[n];if(s)return s;n++}return t.length<e.length?t.length===1&&t[0]===80?-1:1:t.length>e.length?e.length===1&&e[0]===80?1:-1:0}function Dv(t,e){let n=0;const s=t.score,i=e.score;for(;n<s.length&&n<i.length;){const r=T1(s[n],i[n]);if(r)return r;n++}if(Math.abs(i.length-s.length)===1){if(Xg(s))return 1;if(Xg(i))return-1}return i.length-s.length}function Xg(t){const e=t[t.length-1];return t.length>0&&e[e.length-1]<0}const C1={type:0,value:""},I1=/[a-zA-Z0-9_]/;function S1(t){if(!t)return[[]];if(t==="/")return[[C1]];if(!t.startsWith("/"))throw new Error(`Invalid path "${t}"`);function e(m){throw new Error(`ERR (${n})/"${u}": ${m}`)}let n=0,s=n;const i=[];let r;function o(){r&&i.push(r),r=[]}let l=0,c,u="",h="";function f(){u&&(n===0?r.push({type:0,value:u}):n===1||n===2||n===3?(r.length>1&&(c==="*"||c==="+")&&e(`A repeatable param (${u}) must be alone in its segment. eg: '/:ids+.`),r.push({type:1,value:u,regexp:h,repeatable:c==="*"||c==="+",optional:c==="*"||c==="?"})):e("Invalid state to consume buffer"),u="")}function g(){u+=c}for(;l<t.length;){if(c=t[l++],c==="\\"&&n!==2){s=n,n=4;continue}switch(n){case 0:c==="/"?(u&&f(),o()):c===":"?(f(),n=1):g();break;case 4:g(),n=s;break;case 1:c==="("?n=2:I1.test(c)?g():(f(),n=0,c!=="*"&&c!=="?"&&c!=="+"&&l--);break;case 2:c===")"?h[h.length-1]=="\\"?h=h.slice(0,-1)+c:n=3:h+=c;break;case 3:f(),n=0,c!=="*"&&c!=="?"&&c!=="+"&&l--,h="";break;default:e("Unknown state");break}}return n===2&&e(`Unfinished custom RegExp for param "${u}"`),f(),o(),i}function A1(t,e,n){const s=E1(S1(t.path),n),i=Ae(s,{record:t,parent:e,children:[],alias:[]});return e&&!i.record.aliasOf==!e.record.aliasOf&&e.children.push(i),i}function k1(t,e){const n=[],s=new Map;e=em({strict:!1,end:!0,sensitive:!1},e);function i(f){return s.get(f)}function r(f,g,m){const I=!m,P=Qg(f);P.aliasOf=m&&m.record;const D=em(e,f),M=[P];if("alias"in f){const R=typeof f.alias=="string"?[f.alias]:f.alias;for(const N of R)M.push(Qg(Ae({},P,{components:m?m.record.components:P.components,path:N,aliasOf:m?m.record:P})))}let x,b;for(const R of M){const{path:N}=R;if(g&&N[0]!=="/"){const F=g.record.path,T=F[F.length-1]==="/"?"":"/";R.path=g.record.path+(N&&T+N)}if(x=A1(R,g,D),m?m.alias.push(x):(b=b||x,b!==x&&b.alias.push(x),I&&f.name&&!Zg(x)&&o(f.name)),Lv(x)&&c(x),P.children){const F=P.children;for(let T=0;T<F.length;T++)r(F[T],x,m&&m.children[T])}m=m||x}return b?()=>{o(b)}:so}function o(f){if(Nv(f)){const g=s.get(f);g&&(s.delete(f),n.splice(n.indexOf(g),1),g.children.forEach(o),g.alias.forEach(o))}else{const g=n.indexOf(f);g>-1&&(n.splice(g,1),f.record.name&&s.delete(f.record.name),f.children.forEach(o),f.alias.forEach(o))}}function l(){return n}function c(f){const g=O1(f,n);n.splice(g,0,f),f.record.name&&!Zg(f)&&s.set(f.record.name,f)}function u(f,g){let m,I={},P,D;if("name"in f&&f.name){if(m=s.get(f.name),!m)throw dr(1,{location:f});D=m.record.name,I=Ae(Jg(g.params,m.keys.filter(b=>!b.optional).concat(m.parent?m.parent.keys.filter(b=>b.optional):[]).map(b=>b.name)),f.params&&Jg(f.params,m.keys.map(b=>b.name))),P=m.stringify(I)}else if(f.path!=null)P=f.path,m=n.find(b=>b.re.test(P)),m&&(I=m.parse(P),D=m.record.name);else{if(m=g.name?s.get(g.name):n.find(b=>b.re.test(g.path)),!m)throw dr(1,{location:f,currentLocation:g});D=m.record.name,I=Ae({},g.params,f.params),P=m.stringify(I)}const M=[];let x=m;for(;x;)M.unshift(x.record),x=x.parent;return{name:D,path:P,params:I,matched:M,meta:P1(M)}}t.forEach(f=>r(f));function h(){n.length=0,s.clear()}return{addRoute:r,resolve:u,removeRoute:o,clearRoutes:h,getRoutes:l,getRecordMatcher:i}}function Jg(t,e){const n={};for(const s of e)s in t&&(n[s]=t[s]);return n}function Qg(t){const e={path:t.path,redirect:t.redirect,name:t.name,meta:t.meta||{},aliasOf:t.aliasOf,beforeEnter:t.beforeEnter,props:R1(t),children:t.children||[],instances:{},leaveGuards:new Set,updateGuards:new Set,enterCallbacks:{},components:"components"in t?t.components||null:t.component&&{default:t.component}};return Object.defineProperty(e,"mods",{value:{}}),e}function R1(t){const e={},n=t.props||!1;if("component"in t)e.default=n;else for(const s in t.components)e[s]=typeof n=="object"?n[s]:n;return e}function Zg(t){for(;t;){if(t.record.aliasOf)return!0;t=t.parent}return!1}function P1(t){return t.reduce((e,n)=>Ae(e,n.meta),{})}function em(t,e){const n={};for(const s in t)n[s]=s in e?e[s]:t[s];return n}function O1(t,e){let n=0,s=e.length;for(;n!==s;){const r=n+s>>1;Dv(t,e[r])<0?s=r:n=r+1}const i=N1(t);return i&&(s=e.lastIndexOf(i,s-1)),s}function N1(t){let e=t;for(;e=e.parent;)if(Lv(e)&&Dv(t,e)===0)return e}function Lv({record:t}){return!!(t.name||t.components&&Object.keys(t.components).length||t.redirect)}function x1(t){const e={};if(t===""||t==="?")return e;const s=(t[0]==="?"?t.slice(1):t).split("&");for(let i=0;i<s.length;++i){const r=s[i].replace(Iv," "),o=r.indexOf("="),l=To(o<0?r:r.slice(0,o)),c=o<0?null:To(r.slice(o+1));if(l in e){let u=e[l];Tn(u)||(u=e[l]=[u]),u.push(c)}else e[l]=c}return e}function tm(t){let e="";for(let n in t){const s=t[n];if(n=ZP(n),s==null){s!==void 0&&(e+=(e.length?"&":"")+n);continue}(Tn(s)?s.map(r=>r&&wh(r)):[s&&wh(s)]).forEach(r=>{r!==void 0&&(e+=(e.length?"&":"")+n,r!=null&&(e+="="+r))})}return e}function D1(t){const e={};for(const n in t){const s=t[n];s!==void 0&&(e[n]=Tn(s)?s.map(i=>i==null?null:""+i):s==null?s:""+s)}return e}const L1=Symbol(""),nm=Symbol(""),uc=Symbol(""),Dd=Symbol(""),bh=Symbol("");function jr(){let t=[];function e(s){return t.push(s),()=>{const i=t.indexOf(s);i>-1&&t.splice(i,1)}}function n(){t=[]}return{add:e,list:()=>t.slice(),reset:n}}function Cs(t,e,n,s,i,r=o=>o()){const o=s&&(s.enterCallbacks[i]=s.enterCallbacks[i]||[]);return()=>new Promise((l,c)=>{const u=g=>{g===!1?c(dr(4,{from:n,to:e})):g instanceof Error?c(g):w1(g)?c(dr(2,{from:e,to:g})):(o&&s.enterCallbacks[i]===o&&typeof g=="function"&&o.push(g),l())},h=r(()=>t.call(s&&s.instances[i],e,n,u));let f=Promise.resolve(h);t.length<3&&(f=f.then(u)),f.catch(g=>c(g))})}function gu(t,e,n,s,i=r=>r()){const r=[];for(const o of t)for(const l in o.components){let c=o.components[l];if(!(e!=="beforeRouteEnter"&&!o.instances[l]))if(Tv(c)){const h=(c.__vccOpts||c)[e];h&&r.push(Cs(h,n,s,o,l,i))}else{let u=c();r.push(()=>u.then(h=>{if(!h)throw new Error(`Couldn't resolve component "${l}" at "${o.path}"`);const f=jP(h)?h.default:h;o.mods[l]=h,o.components[l]=f;const m=(f.__vccOpts||f)[e];return m&&Cs(m,n,s,o,l,i)()}))}}return r}function sm(t){const e=ht(uc),n=ht(Dd),s=an(()=>{const c=Pe(t.to);return e.resolve(c)}),i=an(()=>{const{matched:c}=s.value,{length:u}=c,h=c[u-1],f=n.matched;if(!h||!f.length)return-1;const g=f.findIndex(hr.bind(null,h));if(g>-1)return g;const m=im(c[u-2]);return u>1&&im(h)===m&&f[f.length-1].path!==m?f.findIndex(hr.bind(null,c[u-2])):g}),r=an(()=>i.value>-1&&$1(n.params,s.value.params)),o=an(()=>i.value>-1&&i.value===n.matched.length-1&&Rv(n.params,s.value.params));function l(c={}){return U1(c)?e[Pe(t.replace)?"replace":"push"](Pe(t.to)).catch(so):Promise.resolve()}return{route:s,href:an(()=>s.value.href),isActive:r,isExactActive:o,navigate:l}}const M1=ls({name:"RouterLink",compatConfig:{MODE:3},props:{to:{type:[String,Object],required:!0},replace:Boolean,activeClass:String,exactActiveClass:String,custom:Boolean,ariaCurrentValue:{type:String,default:"page"}},useLink:sm,setup(t,{slots:e}){const n=In(sm(t)),{options:s}=ht(uc),i=an(()=>({[rm(t.activeClass,s.linkActiveClass,"router-link-active")]:n.isActive,[rm(t.exactActiveClass,s.linkExactActiveClass,"router-link-exact-active")]:n.isExactActive}));return()=>{const r=e.default&&e.default(n);return t.custom?r:jt("a",{"aria-current":n.isExactActive?t.ariaCurrentValue:null,href:n.href,onClick:n.navigate,class:i.value},r)}}}),F1=M1;function U1(t){if(!(t.metaKey||t.altKey||t.ctrlKey||t.shiftKey)&&!t.defaultPrevented&&!(t.button!==void 0&&t.button!==0)){if(t.currentTarget&&t.currentTarget.getAttribute){const e=t.currentTarget.getAttribute("target");if(/\b_blank\b/i.test(e))return}return t.preventDefault&&t.preventDefault(),!0}}function $1(t,e){for(const n in e){const s=e[n],i=t[n];if(typeof s=="string"){if(s!==i)return!1}else if(!Tn(i)||i.length!==s.length||s.some((r,o)=>r!==i[o]))return!1}return!0}function im(t){return t?t.aliasOf?t.aliasOf.path:t.path:""}const rm=(t,e,n)=>t??e??n,H1=ls({name:"RouterView",inheritAttrs:!1,props:{name:{type:String,default:"default"},route:Object},compatConfig:{MODE:3},setup(t,{attrs:e,slots:n}){const s=ht(bh),i=an(()=>t.route||s.value),r=ht(nm,0),o=an(()=>{let u=Pe(r);const{matched:h}=i.value;let f;for(;(f=h[u])&&!f.components;)u++;return u}),l=an(()=>i.value.matched[o.value]);ai(nm,an(()=>o.value+1)),ai(L1,l),ai(bh,i);const c=Ge();return li(()=>[c.value,l.value,t.name],([u,h,f],[g,m,I])=>{h&&(h.instances[f]=u,m&&m!==h&&u&&u===g&&(h.leaveGuards.size||(h.leaveGuards=m.leaveGuards),h.updateGuards.size||(h.updateGuards=m.updateGuards))),u&&h&&(!m||!hr(h,m)||!g)&&(h.enterCallbacks[f]||[]).forEach(P=>P(u))},{flush:"post"}),()=>{const u=i.value,h=t.name,f=l.value,g=f&&f.components[h];if(!g)return om(n.default,{Component:g,route:u});const m=f.props[h],I=m?m===!0?u.params:typeof m=="function"?m(u):m:null,D=jt(g,Ae({},I,e,{onVnodeUnmounted:M=>{M.component.isUnmounted&&(f.instances[h]=null)},ref:c}));return om(n.default,{Component:D,route:u})||D}}});function om(t,e){if(!t)return null;const n=t(e);return n.length===1?n[0]:n}const Mv=H1;function B1(t){const e=k1(t.routes,t),n=t.parseQuery||x1,s=t.stringifyQuery||tm,i=t.history,r=jr(),o=jr(),l=jr(),c=go(gn);let u=gn;Hi&&t.scrollBehavior&&"scrollRestoration"in history&&(history.scrollRestoration="manual");const h=fu.bind(null,B=>""+B),f=fu.bind(null,t1),g=fu.bind(null,To);function m(B,Q){let X,te;return Nv(B)?(X=e.getRecordMatcher(B),te=Q):te=B,e.addRoute(te,X)}function I(B){const Q=e.getRecordMatcher(B);Q&&e.removeRoute(Q)}function P(){return e.getRoutes().map(B=>B.record)}function D(B){return!!e.getRecordMatcher(B)}function M(B,Q){if(Q=Ae({},Q||c.value),typeof B=="string"){const k=pu(n,B,Q.path),L=e.resolve({path:k.path},Q),j=i.createHref(k.fullPath);return Ae(k,L,{params:g(L.params),hash:To(k.hash),redirectedFrom:void 0,href:j})}let X;if(B.path!=null)X=Ae({},B,{path:pu(n,B.path,Q.path).path});else{const k=Ae({},B.params);for(const L in k)k[L]==null&&delete k[L];X=Ae({},B,{params:f(k)}),Q.params=f(Q.params)}const te=e.resolve(X,Q),de=B.hash||"";te.params=h(g(te.params));const Re=i1(s,Ae({},B,{hash:QP(de),path:te.path})),C=i.createHref(Re);return Ae({fullPath:Re,hash:de,query:s===tm?D1(B.query):B.query||{}},te,{redirectedFrom:void 0,href:C})}function x(B){return typeof B=="string"?pu(n,B,c.value.path):Ae({},B)}function b(B,Q){if(u!==B)return dr(8,{from:Q,to:B})}function R(B){return T(B)}function N(B){return R(Ae(x(B),{replace:!0}))}function F(B){const Q=B.matched[B.matched.length-1];if(Q&&Q.redirect){const{redirect:X}=Q;let te=typeof X=="function"?X(B):X;return typeof te=="string"&&(te=te.includes("?")||te.includes("#")?te=x(te):{path:te},te.params={}),Ae({query:B.query,hash:B.hash,params:te.path!=null?{}:B.params},te)}}function T(B,Q){const X=u=M(B),te=c.value,de=B.state,Re=B.force,C=B.replace===!0,k=F(X);if(k)return T(Ae(x(k),{state:typeof k=="object"?Ae({},de,k.state):de,force:Re,replace:C}),Q||X);const L=X;L.redirectedFrom=Q;let j;return!Re&&r1(s,te,X)&&(j=dr(16,{to:L,from:te}),tn(te,te,!0,!1)),(j?Promise.resolve(j):v(L,te)).catch(H=>Vn(H)?Vn(H,2)?H:fn(H):Z(H,L,te)).then(H=>{if(H){if(Vn(H,2))return T(Ae({replace:C},x(H.to),{state:typeof H.to=="object"?Ae({},de,H.to.state):de,force:Re}),Q||L)}else H=S(L,te,!0,C,de);return A(L,te,H),H})}function w(B,Q){const X=b(B,Q);return X?Promise.reject(X):Promise.resolve()}function y(B){const Q=hs.values().next().value;return Q&&typeof Q.runWithContext=="function"?Q.runWithContext(B):B()}function v(B,Q){let X;const[te,de,Re]=j1(B,Q);X=gu(te.reverse(),"beforeRouteLeave",B,Q);for(const k of te)k.leaveGuards.forEach(L=>{X.push(Cs(L,B,Q))});const C=w.bind(null,B,Q);return X.push(C),Ft(X).then(()=>{X=[];for(const k of r.list())X.push(Cs(k,B,Q));return X.push(C),Ft(X)}).then(()=>{X=gu(de,"beforeRouteUpdate",B,Q);for(const k of de)k.updateGuards.forEach(L=>{X.push(Cs(L,B,Q))});return X.push(C),Ft(X)}).then(()=>{X=[];for(const k of Re)if(k.beforeEnter)if(Tn(k.beforeEnter))for(const L of k.beforeEnter)X.push(Cs(L,B,Q));else X.push(Cs(k.beforeEnter,B,Q));return X.push(C),Ft(X)}).then(()=>(B.matched.forEach(k=>k.enterCallbacks={}),X=gu(Re,"beforeRouteEnter",B,Q,y),X.push(C),Ft(X))).then(()=>{X=[];for(const k of o.list())X.push(Cs(k,B,Q));return X.push(C),Ft(X)}).catch(k=>Vn(k,8)?k:Promise.reject(k))}function A(B,Q,X){l.list().forEach(te=>y(()=>te(B,Q,X)))}function S(B,Q,X,te,de){const Re=b(B,Q);if(Re)return Re;const C=Q===gn,k=Hi?history.state:{};X&&(te||C?i.replace(B.fullPath,Ae({scroll:C&&k&&k.scroll},de)):i.push(B.fullPath,de)),c.value=B,tn(B,Q,X,C),fn()}let E;function he(){E||(E=i.listen((B,Q,X)=>{if(!An.listening)return;const te=M(B),de=F(te);if(de){T(Ae(de,{replace:!0}),te).catch(so);return}u=te;const Re=c.value;Hi&&f1(qg(Re.fullPath,X.delta),cc()),v(te,Re).catch(C=>Vn(C,12)?C:Vn(C,2)?(T(C.to,te).then(k=>{Vn(k,20)&&!X.delta&&X.type===Co.pop&&i.go(-1,!1)}).catch(so),Promise.reject()):(X.delta&&i.go(-X.delta,!1),Z(C,te,Re))).then(C=>{C=C||S(te,Re,!1),C&&(X.delta&&!Vn(C,8)?i.go(-X.delta,!1):X.type===Co.pop&&Vn(C,20)&&i.go(-1,!1)),A(te,Re,C)}).catch(so)}))}let pe=jr(),ee=jr(),le;function Z(B,Q,X){fn(B);const te=ee.list();return te.length?te.forEach(de=>de(B,Q,X)):console.error(B),Promise.reject(B)}function Ve(){return le&&c.value!==gn?Promise.resolve():new Promise((B,Q)=>{pe.add([B,Q])})}function fn(B){return le||(le=!B,he(),pe.list().forEach(([Q,X])=>B?X(B):Q()),pe.reset()),B}function tn(B,Q,X,te){const{scrollBehavior:de}=t;if(!Hi||!de)return Promise.resolve();const Re=!X&&p1(qg(B.fullPath,0))||(te||!X)&&history.state&&history.state.scroll||null;return bi().then(()=>de(B,Q,Re)).then(C=>C&&d1(C)).catch(C=>Z(C,B,Q))}const We=B=>i.go(B);let Ke;const hs=new Set,An={currentRoute:c,listening:!0,addRoute:m,removeRoute:I,clearRoutes:e.clearRoutes,hasRoute:D,getRoutes:P,resolve:M,options:t,push:R,replace:N,go:We,back:()=>We(-1),forward:()=>We(1),beforeEach:r.add,beforeResolve:o.add,afterEach:l.add,onError:ee.add,isReady:Ve,install(B){const Q=this;B.component("RouterLink",F1),B.component("RouterView",Mv),B.config.globalProperties.$router=Q,Object.defineProperty(B.config.globalProperties,"$route",{enumerable:!0,get:()=>Pe(c)}),Hi&&!Ke&&c.value===gn&&(Ke=!0,R(i.location).catch(de=>{}));const X={};for(const de in gn)Object.defineProperty(X,de,{get:()=>c.value[de],enumerable:!0});B.provide(uc,Q),B.provide(Dd,Gn(X)),B.provide(bh,c);const te=B.unmount;hs.add(B),B.unmount=function(){hs.delete(B),hs.size<1&&(u=gn,E&&E(),E=null,c.value=gn,Ke=!1,le=!1),te()}}};function Ft(B){return B.reduce((Q,X)=>Q.then(()=>y(X)),Promise.resolve())}return An}function j1(t,e){const n=[],s=[],i=[],r=Math.max(e.matched.length,t.matched.length);for(let o=0;o<r;o++){const l=e.matched[o];l&&(t.matched.find(u=>hr(u,l))?s.push(l):n.push(l));const c=t.matched[o];c&&(e.matched.find(u=>hr(u,c))||i.push(c))}return[n,s,i]}function pH(){return ht(uc)}function V1(t){return ht(Dd)}const W1=(t,e)=>e.path.replace(/(:\w+)\([^)]+\)/g,"$1").replace(/(:\w+)[?+*]/g,"$1").replace(/:\w+/g,n=>{var s;return((s=t.params[n.slice(1)])==null?void 0:s.toString())||""}),Eh=(t,e)=>{const n=t.route.matched.find(i=>{var r;return((r=i.components)==null?void 0:r.default)===t.Component.type}),s=e??(n==null?void 0:n.meta.key)??(n&&W1(t.route,n));return typeof s=="function"?s(t.route):s},K1=(t,e)=>({default:()=>t?jt(WC,t===!0?{}:t,e):e});function Ld(t){return Array.isArray(t)?t:[t]}const q1="modulepreload",z1=function(t,e){return new URL(t,e).href},am={},qe=function(e,n,s){let i=Promise.resolve();if(n&&n.length>0){const o=document.getElementsByTagName("link"),l=document.querySelector("meta[property=csp-nonce]"),c=(l==null?void 0:l.nonce)||(l==null?void 0:l.getAttribute("nonce"));i=Promise.allSettled(n.map(u=>{if(u=z1(u,s),u in am)return;am[u]=!0;const h=u.endsWith(".css"),f=h?'[rel="stylesheet"]':"";if(!!s)for(let I=o.length-1;I>=0;I--){const P=o[I];if(P.href===u&&(!h||P.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${u}"]${f}`))return;const m=document.createElement("link");if(m.rel=h?"stylesheet":q1,h||(m.as="script"),m.crossOrigin="",m.href=u,c&&m.setAttribute("nonce",c),document.head.appendChild(m),h)return new Promise((I,P)=>{m.addEventListener("load",I),m.addEventListener("error",()=>P(new Error(`Unable to preload CSS for ${u}`)))})}))}function r(o){const l=new Event("vite:preloadError",{cancelable:!0});if(l.payload=o,window.dispatchEvent(l),!l.defaultPrevented)throw o}return i.then(o=>{for(const l of o||[])l.status==="rejected"&&r(l.reason);return e().catch(r)})},mu=[{name:"account",path:"/account",component:()=>qe(()=>import("./index-BKzPDcje.js"),__vite__mapDeps([0,1,2,3]),import.meta.url)},{name:"auth-forgot",path:"/auth/forgot",component:()=>qe(()=>import("./forgot-BajuqLfH.js"),__vite__mapDeps([4,1,5,3]),import.meta.url)},{name:"auth",path:"/auth",component:()=>qe(()=>import("./index-Dk2vUOhB.js"),__vite__mapDeps([6,1,5,3]),import.meta.url)},{name:"auth-reset",path:"/auth/reset",component:()=>qe(()=>import("./reset-DSL6HENl.js"),__vite__mapDeps([7,8]),import.meta.url)},{name:"auth-verify",path:"/auth/verify",component:()=>qe(()=>import("./verify-COF2ROIM.js"),__vite__mapDeps([9,8]),import.meta.url)},{name:"faq",path:"/faq",component:()=>qe(()=>import("./faq-DIfNe5Sa.js"),__vite__mapDeps([10,11,1,3]),import.meta.url)},{name:"home",path:"/home",component:()=>qe(()=>import("./home-BUJmxL0Q.js"),__vite__mapDeps([12,13,14,3,15,2,16,17,18]),import.meta.url)},{name:"index",path:"/",component:()=>qe(()=>import("./index-DXrcwqc6.js"),[],import.meta.url)},{name:"manual-id",path:"/manual/:id()",component:()=>qe(()=>import("./_id_-C8cZMSFE.js"),__vite__mapDeps([19,11,14,1,3]),import.meta.url)},{name:"manual-capture",path:"/manual/capture",component:()=>qe(()=>import("./capture-DTTns17F.js"),__vite__mapDeps([20,11,21,16]),import.meta.url)},{name:"manual",path:"/manual",component:()=>qe(()=>import("./index-fH9qp2FD.js"),__vite__mapDeps([22,2,3,23,21,16]),import.meta.url)},{name:"notifications",path:"/notifications",component:()=>qe(()=>import("./notifications-DT7U4xw0.js"),__vite__mapDeps([24,11,13,1,21,16]),import.meta.url)},{name:"overtime-id",path:"/overtime/:id()",component:()=>qe(()=>import("./_id_-CKw8sDIf.js"),__vite__mapDeps([25,11,14,1]),import.meta.url)},{name:"overtime",path:"/overtime",component:()=>qe(()=>import("./index-CWyZnB_2.js"),__vite__mapDeps([26,11,27,23,28,29,1]),import.meta.url)},{name:"overtime-list",path:"/overtime/list",component:()=>qe(()=>import("./list-NpYGgzD-.js"),__vite__mapDeps([30,11,13,14,3,15]),import.meta.url)},{name:"permit-id",path:"/permit/:id()",component:()=>qe(()=>import("./_id_-B-e0_Gej.js"),__vite__mapDeps([31,11,14,1,3,17]),import.meta.url)},{name:"permit-done",path:"/permit/done",component:()=>qe(()=>import("./done-B7oAxAZB.js"),__vite__mapDeps([32,11,1,8]),import.meta.url)},{name:"permit",path:"/permit",component:()=>qe(()=>import("./index-BRxfCamj.js"),__vite__mapDeps([33,11,1,23,27,28,29,3,17,34]),import.meta.url)},{name:"permit-manual copy",path:"/permit/manual%20copy",component:()=>qe(()=>import("./manual copy-BIUL7_Bg.js"),__vite__mapDeps([35,11,3,13,1,29,23,21,16]),import.meta.url)}],Fv=(t,e,n)=>(e=e===!0?{}:e,{default:()=>{var s;return e?jt(t,e,n):(s=n.default)==null?void 0:s.call(n)}});function lm(t){const e=(t==null?void 0:t.meta.key)??t.path.replace(/(:\w+)\([^)]+\)/g,"$1").replace(/(:\w+)[?+*]/g,"$1").replace(/:\w+/g,n=>{var s;return((s=t.params[n.slice(1)])==null?void 0:s.toString())||""});return typeof e=="function"?e(t):e}function G1(t,e){return t===e||e===gn?!1:lm(t)!==lm(e)?!0:!t.matched.every((s,i)=>{var r,o;return s.components&&s.components.default===((o=(r=e.matched[i])==null?void 0:r.components)==null?void 0:o.default)})}const Y1={scrollBehavior(t,e,n){var u;const s=Je(),i=((u=hn().options)==null?void 0:u.scrollBehaviorType)??"auto";let r=n||void 0;const o=typeof t.meta.scrollToTop=="function"?t.meta.scrollToTop(t,e):t.meta.scrollToTop;if(!r&&e&&t&&o!==!1&&G1(t,e)&&(r={left:0,top:0}),t.path===e.path)return e.hash&&!t.hash?{left:0,top:0}:t.hash?{el:t.hash,top:cm(t.hash),behavior:i}:!1;const l=h=>!!(h.meta.pageTransition??sh),c=l(e)&&l(t)?"page:transition:finish":"page:finish";return new Promise(h=>{s.hooks.hookOnce(c,async()=>{await new Promise(f=>setTimeout(f,0)),t.hash&&(r={el:t.hash,top:cm(t.hash),behavior:i}),h(r)})})}};function cm(t){try{const e=document.querySelector(t);if(e)return(Number.parseFloat(getComputedStyle(e).scrollMarginTop)||0)+(Number.parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop)||0)}catch{}return 0}const X1={hashMode:!1,scrollBehaviorType:"auto"},on={...X1,...Y1},J1=async t=>{var c;let e,n;if(!((c=t.meta)!=null&&c.validate))return;const s=Je(),i=hn(),r=([e,n]=Qi(()=>Promise.resolve(t.meta.validate(t))),e=await e,n(),e);if(r===!0)return;const o=sc({statusCode:r&&r.statusCode||404,statusMessage:r&&r.statusMessage||`Page Not Found: ${t.fullPath}`,data:{path:t.fullPath}}),l=i.beforeResolve(u=>{if(l(),u===t){const h=i.afterEach(async()=>{h(),await s.runWithContext(()=>ji(o)),window==null||window.history.pushState({},"",t.fullPath)});return!1}})},Q1={auth_login:{url:"/employeeapi/auth/login",auth:!1,method:"POST"},auth_me:{url:"/employeeapi/auth/me",auth:!0,method:"GET"},auth_logout:{url:"/employeeapi/auth/logout",auth:!0,method:"POST"},profile:{url:"/v1/records/users",auth:!0,method:"GET"},faq:{url:"/employeeapi/faqs",auth:!0,method:"GET"},get_forms:{url:"/employeeapi/permits",auth:!0,method:"GET"},upload:{url:"/employeeapi/upload",auth:!0,method:"POST"},get_quota:{url:"/v1/records/leave_quotas",auth:!0,method:"GET"},set_quota:{url:"/v1/records/leave_quotas",auth:!0,method:"POST"},update_quota:{url:"/v1/records/leave_quotas",auth:!0,method:"PUT"},get_holidays:{url:"/v1/records/national_holidays",auth:!0,method:"GET"},set_holidays:{url:"/v1/records/national_holidays",auth:!0,method:"POST"},get_settings:{url:"/v1/records/settings",auth:!0,method:"GET"},set_form:{url:"/employeeapi/permits",auth:!0,method:"POST"},update_form:{url:"/employeeapi/permits",auth:!0,method:"PUT"},get_overtimes:{url:"/employeeapi/overtimes",auth:!0,method:"GET"},set_overtime:{url:"/employeeapi/overtimes",auth:!0,method:"POST"},update_overtime:{url:"/employeeapi/overtimes",auth:!0,method:"PUT"},post_manual:{url:"/employeeapi/attendances",auth:!0,method:"POST"},get_dinas_clocks:{url:"/employeeapi/attendances",auth:!0,method:"GET"},update_dinas_clocks:{url:"/employeeapi/attendances",auth:!0,method:"PUT"},set_token:{url:"/v1/records/notif_tokens",auth:!0,method:"POST"},delete_token:{url:"/v1/records/notif_tokens",auth:!0,method:"DELETE"},get_tokens:{url:"/v1/records/notif_tokens",auth:!0,method:"GET"},get_notif:{url:"/v1/records/notifications",auth:!0,method:"GET"},set_notif:{url:"/v1/records/notifications",auth:!0,method:"POST"},update_notif:{url:"/v1/records/notifications",auth:!0,method:"PUT"},notify:{url:"/v3/notify",auth:!0,method:"POST"}},Z1="https://accounting.wismaatlet.id/api",gH="https://accounting.wismaatlet.id/";var e2="firebase",t2="10.13.2";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */mt(e2,t2,"app");const Uv="@firebase/installations",Md="0.6.9";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const $v=1e4,Hv=`w:${Md}`,Bv="FIS_v2",n2="https://firebaseinstallations.googleapis.com/v1",s2=60*60*1e3,i2="installations",r2="Installations";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const o2={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"not-registered":"Firebase Installation is not registered.","installation-not-found":"Firebase Installation not found.","request-failed":'{$requestName} request failed with error "{$serverCode} {$serverStatus}: {$serverMessage}"',"app-offline":"Could not process request. Application offline.","delete-pending-registration":"Can't delete installation while there is a pending registration request."},di=new Bs(i2,r2,o2);function jv(t){return t instanceof Sn&&t.code.includes("request-failed")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Vv({projectId:t}){return`${n2}/projects/${t}/installations`}function Wv(t){return{token:t.token,requestStatus:2,expiresIn:l2(t.expiresIn),creationTime:Date.now()}}async function Kv(t,e){const s=(await e.json()).error;return di.create("request-failed",{requestName:t,serverCode:s.code,serverMessage:s.message,serverStatus:s.status})}function qv({apiKey:t}){return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":t})}function a2(t,{refreshToken:e}){const n=qv(t);return n.append("Authorization",c2(e)),n}async function zv(t){const e=await t();return e.status>=500&&e.status<600?t():e}function l2(t){return Number(t.replace("s","000"))}function c2(t){return`${Bv} ${t}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function u2({appConfig:t,heartbeatServiceProvider:e},{fid:n}){const s=Vv(t),i=qv(t),r=e.getImmediate({optional:!0});if(r){const u=await r.getHeartbeatsHeader();u&&i.append("x-firebase-client",u)}const o={fid:n,authVersion:Bv,appId:t.appId,sdkVersion:Hv},l={method:"POST",headers:i,body:JSON.stringify(o)},c=await zv(()=>fetch(s,l));if(c.ok){const u=await c.json();return{fid:u.fid||n,registrationStatus:2,refreshToken:u.refreshToken,authToken:Wv(u.authToken)}}else throw await Kv("Create Installation",c)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Gv(t){return new Promise(e=>{setTimeout(e,t)})}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function h2(t){return btoa(String.fromCharCode(...t)).replace(/\+/g,"-").replace(/\//g,"_")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const d2=/^[cdef][\w-]{21}$/,Th="";function f2(){try{const t=new Uint8Array(17);(self.crypto||self.msCrypto).getRandomValues(t),t[0]=112+t[0]%16;const n=p2(t);return d2.test(n)?n:Th}catch{return Th}}function p2(t){return h2(t).substr(0,22)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function hc(t){return`${t.appName}!${t.appId}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Yv=new Map;function Xv(t,e){const n=hc(t);Jv(n,e),g2(n,e)}function Jv(t,e){const n=Yv.get(t);if(n)for(const s of n)s(e)}function g2(t,e){const n=m2();n&&n.postMessage({key:t,fid:e}),_2()}let ni=null;function m2(){return!ni&&"BroadcastChannel"in self&&(ni=new BroadcastChannel("[Firebase] FID Change"),ni.onmessage=t=>{Jv(t.data.key,t.data.fid)}),ni}function _2(){Yv.size===0&&ni&&(ni.close(),ni=null)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const y2="firebase-installations-database",w2=1,fi="firebase-installations-store";let _u=null;function Fd(){return _u||(_u=oc(y2,w2,{upgrade:(t,e)=>{switch(e){case 0:t.createObjectStore(fi)}}})),_u}async function vl(t,e){const n=hc(t),i=(await Fd()).transaction(fi,"readwrite"),r=i.objectStore(fi),o=await r.get(n);return await r.put(e,n),await i.done,(!o||o.fid!==e.fid)&&Xv(t,e.fid),e}async function Qv(t){const e=hc(t),s=(await Fd()).transaction(fi,"readwrite");await s.objectStore(fi).delete(e),await s.done}async function dc(t,e){const n=hc(t),i=(await Fd()).transaction(fi,"readwrite"),r=i.objectStore(fi),o=await r.get(n),l=e(o);return l===void 0?await r.delete(n):await r.put(l,n),await i.done,l&&(!o||o.fid!==l.fid)&&Xv(t,l.fid),l}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Ud(t){let e;const n=await dc(t.appConfig,s=>{const i=v2(s),r=b2(t,i);return e=r.registrationPromise,r.installationEntry});return n.fid===Th?{installationEntry:await e}:{installationEntry:n,registrationPromise:e}}function v2(t){const e=t||{fid:f2(),registrationStatus:0};return Zv(e)}function b2(t,e){if(e.registrationStatus===0){if(!navigator.onLine){const i=Promise.reject(di.create("app-offline"));return{installationEntry:e,registrationPromise:i}}const n={fid:e.fid,registrationStatus:1,registrationTime:Date.now()},s=E2(t,n);return{installationEntry:n,registrationPromise:s}}else return e.registrationStatus===1?{installationEntry:e,registrationPromise:T2(t)}:{installationEntry:e}}async function E2(t,e){try{const n=await u2(t,e);return vl(t.appConfig,n)}catch(n){throw jv(n)&&n.customData.serverCode===409?await Qv(t.appConfig):await vl(t.appConfig,{fid:e.fid,registrationStatus:0}),n}}async function T2(t){let e=await um(t.appConfig);for(;e.registrationStatus===1;)await Gv(100),e=await um(t.appConfig);if(e.registrationStatus===0){const{installationEntry:n,registrationPromise:s}=await Ud(t);return s||n}return e}function um(t){return dc(t,e=>{if(!e)throw di.create("installation-not-found");return Zv(e)})}function Zv(t){return C2(t)?{fid:t.fid,registrationStatus:0}:t}function C2(t){return t.registrationStatus===1&&t.registrationTime+$v<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function I2({appConfig:t,heartbeatServiceProvider:e},n){const s=S2(t,n),i=a2(t,n),r=e.getImmediate({optional:!0});if(r){const u=await r.getHeartbeatsHeader();u&&i.append("x-firebase-client",u)}const o={installation:{sdkVersion:Hv,appId:t.appId}},l={method:"POST",headers:i,body:JSON.stringify(o)},c=await zv(()=>fetch(s,l));if(c.ok){const u=await c.json();return Wv(u)}else throw await Kv("Generate Auth Token",c)}function S2(t,{fid:e}){return`${Vv(t)}/${e}/authTokens:generate`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function $d(t,e=!1){let n;const s=await dc(t.appConfig,r=>{if(!eb(r))throw di.create("not-registered");const o=r.authToken;if(!e&&R2(o))return r;if(o.requestStatus===1)return n=A2(t,e),r;{if(!navigator.onLine)throw di.create("app-offline");const l=O2(r);return n=k2(t,l),l}});return n?await n:s.authToken}async function A2(t,e){let n=await hm(t.appConfig);for(;n.authToken.requestStatus===1;)await Gv(100),n=await hm(t.appConfig);const s=n.authToken;return s.requestStatus===0?$d(t,e):s}function hm(t){return dc(t,e=>{if(!eb(e))throw di.create("not-registered");const n=e.authToken;return N2(n)?Object.assign(Object.assign({},e),{authToken:{requestStatus:0}}):e})}async function k2(t,e){try{const n=await I2(t,e),s=Object.assign(Object.assign({},e),{authToken:n});return await vl(t.appConfig,s),n}catch(n){if(jv(n)&&(n.customData.serverCode===401||n.customData.serverCode===404))await Qv(t.appConfig);else{const s=Object.assign(Object.assign({},e),{authToken:{requestStatus:0}});await vl(t.appConfig,s)}throw n}}function eb(t){return t!==void 0&&t.registrationStatus===2}function R2(t){return t.requestStatus===2&&!P2(t)}function P2(t){const e=Date.now();return e<t.creationTime||t.creationTime+t.expiresIn<e+s2}function O2(t){const e={requestStatus:1,requestTime:Date.now()};return Object.assign(Object.assign({},t),{authToken:e})}function N2(t){return t.requestStatus===1&&t.requestTime+$v<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function x2(t){const e=t,{installationEntry:n,registrationPromise:s}=await Ud(e);return s?s.catch(console.error):$d(e).catch(console.error),n.fid}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function D2(t,e=!1){const n=t;return await L2(n),(await $d(n,e)).token}async function L2(t){const{registrationPromise:e}=await Ud(t);e&&await e}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function M2(t){if(!t||!t.options)throw yu("App Configuration");if(!t.name)throw yu("App Name");const e=["projectId","apiKey","appId"];for(const n of e)if(!t.options[n])throw yu(n);return{appName:t.name,projectId:t.options.projectId,apiKey:t.options.apiKey,appId:t.options.appId}}function yu(t){return di.create("missing-app-config-values",{valueName:t})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const tb="installations",F2="installations-internal",U2=t=>{const e=t.getProvider("app").getImmediate(),n=M2(e),s=$o(e,"heartbeat");return{app:e,appConfig:n,heartbeatServiceProvider:s,_delete:()=>Promise.resolve()}},$2=t=>{const e=t.getProvider("app").getImmediate(),n=$o(e,tb).getImmediate();return{getId:()=>x2(n),getToken:i=>D2(n,i)}};function H2(){Kt(new Mt(tb,U2,"PUBLIC")),Kt(new Mt(F2,$2,"PRIVATE"))}H2();mt(Uv,Md);mt(Uv,Md,"esm2017");/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const B2="/firebase-messaging-sw.js",j2="/firebase-cloud-messaging-push-scope",nb="BDOU99-h67HcA6JeFXHbSNMu7e2yNNu3RzoMj8TM4W88jITfq7ZmPvIM1Iv-4_l2LxQcYwhqby2xGpWwzjfAnG4",V2="https://fcmregistrations.googleapis.com/v1",sb="google.c.a.c_id",W2="google.c.a.c_l",K2="google.c.a.ts",q2="google.c.a.e";var dm;(function(t){t[t.DATA_MESSAGE=1]="DATA_MESSAGE",t[t.DISPLAY_NOTIFICATION=3]="DISPLAY_NOTIFICATION"})(dm||(dm={}));/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */var Io;(function(t){t.PUSH_RECEIVED="push-received",t.NOTIFICATION_CLICKED="notification-clicked"})(Io||(Io={}));/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Kn(t){const e=new Uint8Array(t);return btoa(String.fromCharCode(...e)).replace(/=/g,"").replace(/\+/g,"-").replace(/\//g,"_")}function z2(t){const e="=".repeat((4-t.length%4)%4),n=(t+e).replace(/\-/g,"+").replace(/_/g,"/"),s=atob(n),i=new Uint8Array(s.length);for(let r=0;r<s.length;++r)i[r]=s.charCodeAt(r);return i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const wu="fcm_token_details_db",G2=5,fm="fcm_token_object_Store";async function Y2(t){if("databases"in indexedDB&&!(await indexedDB.databases()).map(r=>r.name).includes(wu))return null;let e=null;return(await oc(wu,G2,{upgrade:async(s,i,r,o)=>{var l;if(i<2||!s.objectStoreNames.contains(fm))return;const c=o.objectStore(fm),u=await c.index("fcmSenderId").get(t);if(await c.clear(),!!u){if(i===2){const h=u;if(!h.auth||!h.p256dh||!h.endpoint)return;e={token:h.fcmToken,createTime:(l=h.createTime)!==null&&l!==void 0?l:Date.now(),subscriptionOptions:{auth:h.auth,p256dh:h.p256dh,endpoint:h.endpoint,swScope:h.swScope,vapidKey:typeof h.vapidKey=="string"?h.vapidKey:Kn(h.vapidKey)}}}else if(i===3){const h=u;e={token:h.fcmToken,createTime:h.createTime,subscriptionOptions:{auth:Kn(h.auth),p256dh:Kn(h.p256dh),endpoint:h.endpoint,swScope:h.swScope,vapidKey:Kn(h.vapidKey)}}}else if(i===4){const h=u;e={token:h.fcmToken,createTime:h.createTime,subscriptionOptions:{auth:Kn(h.auth),p256dh:Kn(h.p256dh),endpoint:h.endpoint,swScope:h.swScope,vapidKey:Kn(h.vapidKey)}}}}}})).close(),await cu(wu),await cu("fcm_vapid_details_db"),await cu("undefined"),X2(e)?e:null}function X2(t){if(!t||!t.subscriptionOptions)return!1;const{subscriptionOptions:e}=t;return typeof t.createTime=="number"&&t.createTime>0&&typeof t.token=="string"&&t.token.length>0&&typeof e.auth=="string"&&e.auth.length>0&&typeof e.p256dh=="string"&&e.p256dh.length>0&&typeof e.endpoint=="string"&&e.endpoint.length>0&&typeof e.swScope=="string"&&e.swScope.length>0&&typeof e.vapidKey=="string"&&e.vapidKey.length>0}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const J2="firebase-messaging-database",Q2=1,So="firebase-messaging-store";let vu=null;function ib(){return vu||(vu=oc(J2,Q2,{upgrade:(t,e)=>{switch(e){case 0:t.createObjectStore(So)}}})),vu}async function Z2(t){const e=rb(t),s=await(await ib()).transaction(So).objectStore(So).get(e);if(s)return s;{const i=await Y2(t.appConfig.senderId);if(i)return await Hd(t,i),i}}async function Hd(t,e){const n=rb(t),i=(await ib()).transaction(So,"readwrite");return await i.objectStore(So).put(e,n),await i.done,e}function rb({appConfig:t}){return t.appId}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const eO={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"only-available-in-window":"This method is available in a Window context.","only-available-in-sw":"This method is available in a service worker context.","permission-default":"The notification permission was not granted and dismissed instead.","permission-blocked":"The notification permission was not granted and blocked instead.","unsupported-browser":"This browser doesn't support the API's required to use the Firebase SDK.","indexed-db-unsupported":"This browser doesn't support indexedDb.open() (ex. Safari iFrame, Firefox Private Browsing, etc)","failed-service-worker-registration":"We are unable to register the default service worker. {$browserErrorMessage}","token-subscribe-failed":"A problem occurred while subscribing the user to FCM: {$errorInfo}","token-subscribe-no-token":"FCM returned no token when subscribing the user to push.","token-unsubscribe-failed":"A problem occurred while unsubscribing the user from FCM: {$errorInfo}","token-update-failed":"A problem occurred while updating the user from FCM: {$errorInfo}","token-update-no-token":"FCM returned no token when updating the user to push.","use-sw-after-get-token":"The useServiceWorker() method may only be called once and must be called before calling getToken() to ensure your service worker is used.","invalid-sw-registration":"The input to useServiceWorker() must be a ServiceWorkerRegistration.","invalid-bg-handler":"The input to setBackgroundMessageHandler() must be a function.","invalid-vapid-key":"The public VAPID key must be a string.","use-vapid-key-after-get-token":"The usePublicVapidKey() method may only be called once and must be called before calling getToken() to ensure your VAPID key is used."},St=new Bs("messaging","Messaging",eO);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function tO(t,e){const n=await jd(t),s=ob(e),i={method:"POST",headers:n,body:JSON.stringify(s)};let r;try{r=await(await fetch(Bd(t.appConfig),i)).json()}catch(o){throw St.create("token-subscribe-failed",{errorInfo:o==null?void 0:o.toString()})}if(r.error){const o=r.error.message;throw St.create("token-subscribe-failed",{errorInfo:o})}if(!r.token)throw St.create("token-subscribe-no-token");return r.token}async function nO(t,e){const n=await jd(t),s=ob(e.subscriptionOptions),i={method:"PATCH",headers:n,body:JSON.stringify(s)};let r;try{r=await(await fetch(`${Bd(t.appConfig)}/${e.token}`,i)).json()}catch(o){throw St.create("token-update-failed",{errorInfo:o==null?void 0:o.toString()})}if(r.error){const o=r.error.message;throw St.create("token-update-failed",{errorInfo:o})}if(!r.token)throw St.create("token-update-no-token");return r.token}async function sO(t,e){const s={method:"DELETE",headers:await jd(t)};try{const r=await(await fetch(`${Bd(t.appConfig)}/${e}`,s)).json();if(r.error){const o=r.error.message;throw St.create("token-unsubscribe-failed",{errorInfo:o})}}catch(i){throw St.create("token-unsubscribe-failed",{errorInfo:i==null?void 0:i.toString()})}}function Bd({projectId:t}){return`${V2}/projects/${t}/registrations`}async function jd({appConfig:t,installations:e}){const n=await e.getToken();return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":t.apiKey,"x-goog-firebase-installations-auth":`FIS ${n}`})}function ob({p256dh:t,auth:e,endpoint:n,vapidKey:s}){const i={web:{endpoint:n,auth:e,p256dh:t}};return s!==nb&&(i.web.applicationPubKey=s),i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const iO=7*24*60*60*1e3;async function rO(t){const e=await aO(t.swRegistration,t.vapidKey),n={vapidKey:t.vapidKey,swScope:t.swRegistration.scope,endpoint:e.endpoint,auth:Kn(e.getKey("auth")),p256dh:Kn(e.getKey("p256dh"))},s=await Z2(t.firebaseDependencies);if(s){if(lO(s.subscriptionOptions,n))return Date.now()>=s.createTime+iO?oO(t,{token:s.token,createTime:Date.now(),subscriptionOptions:n}):s.token;try{await sO(t.firebaseDependencies,s.token)}catch(i){console.warn(i)}return pm(t.firebaseDependencies,n)}else return pm(t.firebaseDependencies,n)}async function oO(t,e){try{const n=await nO(t.firebaseDependencies,e),s=Object.assign(Object.assign({},e),{token:n,createTime:Date.now()});return await Hd(t.firebaseDependencies,s),n}catch(n){throw n}}async function pm(t,e){const s={token:await tO(t,e),createTime:Date.now(),subscriptionOptions:e};return await Hd(t,s),s.token}async function aO(t,e){const n=await t.pushManager.getSubscription();return n||t.pushManager.subscribe({userVisibleOnly:!0,applicationServerKey:z2(e)})}function lO(t,e){const n=e.vapidKey===t.vapidKey,s=e.endpoint===t.endpoint,i=e.auth===t.auth,r=e.p256dh===t.p256dh;return n&&s&&i&&r}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function gm(t){const e={from:t.from,collapseKey:t.collapse_key,messageId:t.fcmMessageId};return cO(e,t),uO(e,t),hO(e,t),e}function cO(t,e){if(!e.notification)return;t.notification={};const n=e.notification.title;n&&(t.notification.title=n);const s=e.notification.body;s&&(t.notification.body=s);const i=e.notification.image;i&&(t.notification.image=i);const r=e.notification.icon;r&&(t.notification.icon=r)}function uO(t,e){e.data&&(t.data=e.data)}function hO(t,e){var n,s,i,r,o;if(!e.fcmOptions&&!(!((n=e.notification)===null||n===void 0)&&n.click_action))return;t.fcmOptions={};const l=(i=(s=e.fcmOptions)===null||s===void 0?void 0:s.link)!==null&&i!==void 0?i:(r=e.notification)===null||r===void 0?void 0:r.click_action;l&&(t.fcmOptions.link=l);const c=(o=e.fcmOptions)===null||o===void 0?void 0:o.analytics_label;c&&(t.fcmOptions.analyticsLabel=c)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function dO(t){return typeof t=="object"&&!!t&&sb in t}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */ab("hts/frbslgigp.ogepscmv/ieo/eaylg","tp:/ieaeogn-agolai.o/1frlglgc/o");ab("AzSCbw63g1R0nCw85jG8","Iaya3yLKwmgvh7cF0q4");function ab(t,e){const n=[];for(let s=0;s<t.length;s++)n.push(t.charAt(s)),s<e.length&&n.push(e.charAt(s));return n.join("")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function fO(t){if(!t||!t.options)throw bu("App Configuration Object");if(!t.name)throw bu("App Name");const e=["projectId","apiKey","appId","messagingSenderId"],{options:n}=t;for(const s of e)if(!n[s])throw bu(s);return{appName:t.name,projectId:n.projectId,apiKey:n.apiKey,appId:n.appId,senderId:n.messagingSenderId}}function bu(t){return St.create("missing-app-config-values",{valueName:t})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class pO{constructor(e,n,s){this.deliveryMetricsExportedToBigQueryEnabled=!1,this.onBackgroundMessageHandler=null,this.onMessageHandler=null,this.logEvents=[],this.isLogServiceStarted=!1;const i=fO(e);this.firebaseDependencies={app:e,appConfig:i,installations:n,analyticsProvider:s}}_delete(){return Promise.resolve()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function gO(t){try{t.swRegistration=await navigator.serviceWorker.register(B2,{scope:j2}),t.swRegistration.update().catch(()=>{})}catch(e){throw St.create("failed-service-worker-registration",{browserErrorMessage:e==null?void 0:e.message})}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function mO(t,e){if(!e&&!t.swRegistration&&await gO(t),!(!e&&t.swRegistration)){if(!(e instanceof ServiceWorkerRegistration))throw St.create("invalid-sw-registration");t.swRegistration=e}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function _O(t,e){e?t.vapidKey=e:t.vapidKey||(t.vapidKey=nb)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function lb(t,e){if(!navigator)throw St.create("only-available-in-window");if(Notification.permission==="default"&&await Notification.requestPermission(),Notification.permission!=="granted")throw St.create("permission-blocked");return await _O(t,e==null?void 0:e.vapidKey),await mO(t,e==null?void 0:e.serviceWorkerRegistration),rO(t)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function yO(t,e,n){const s=wO(e);(await t.firebaseDependencies.analyticsProvider.get()).logEvent(s,{message_id:n[sb],message_name:n[W2],message_time:n[K2],message_device_time:Math.floor(Date.now()/1e3)})}function wO(t){switch(t){case Io.NOTIFICATION_CLICKED:return"notification_open";case Io.PUSH_RECEIVED:return"notification_foreground";default:throw new Error}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function vO(t,e){const n=e.data;if(!n.isFirebaseMessaging)return;t.onMessageHandler&&n.messageType===Io.PUSH_RECEIVED&&(typeof t.onMessageHandler=="function"?t.onMessageHandler(gm(n)):t.onMessageHandler.next(gm(n)));const s=n.data;dO(s)&&s[q2]==="1"&&await yO(t,n.messageType,s)}const mm="@firebase/messaging",_m="0.12.11";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const bO=t=>{const e=new pO(t.getProvider("app").getImmediate(),t.getProvider("installations-internal").getImmediate(),t.getProvider("analytics-internal"));return navigator.serviceWorker.addEventListener("message",n=>vO(e,n)),e},EO=t=>{const e=t.getProvider("messaging").getImmediate();return{getToken:s=>lb(e,s)}};function TO(){Kt(new Mt("messaging",bO,"PUBLIC")),Kt(new Mt("messaging-internal",EO,"PRIVATE")),mt(mm,_m),mt(mm,_m,"esm2017")}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function CO(){try{await ev()}catch{return!1}return typeof window<"u"&&Sd()&&ok()&&"serviceWorker"in navigator&&"PushManager"in window&&"Notification"in window&&"fetch"in window&&ServiceWorkerRegistration.prototype.hasOwnProperty("showNotification")&&PushSubscription.prototype.hasOwnProperty("getKey")}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function IO(t,e){if(!navigator)throw St.create("only-available-in-window");return t.onMessageHandler=e,()=>{t.onMessageHandler=null}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function SO(t=Rd()){return CO().then(e=>{if(!e)throw St.create("unsupported-browser")},e=>{throw St.create("indexed-db-unsupported")}),$o(Un(t),"messaging").getImmediate()}async function AO(t,e){return t=Un(t),lb(t,e)}function kO(t,e){return t=Un(t),IO(t,e)}TO();const RO={apiKey:"AIzaSyBS_67f9veXrAsSYnFszPPX0dgae6Bvl6o",authDomain:"absensi-cde0a.firebaseapp.com",projectId:"absensi-cde0a",storageBucket:"absensi-cde0a.appspot.com",messagingSenderId:"672855281448",appId:"1:672855281448:web:001beb135cc2c1d4404a1d"},PO=kd(RO),cb=SO(PO),mH=()=>new Promise((t,e)=>{AO(cb,{vapidKey:"BN1bF0kRzwRFpv9_PG-ePQqNmsm3Je8sZ7BW5NKybRwDPwJJyviuBTkKgFCGKjEDb9fmDIRxAHgpAnDBz95uVb4"}).then(n=>{n?(console.log("Got FCM registration token:",n),t(n)):(console.log("No registration token available. Request permission to generate one."),t(!1))}).catch(n=>{console.error("An error occurred while retrieving token: ",n),t(!1)})});kO(cb,t=>{console.log("Message received. ",t)});const Ch="nuxi_auth_token",ze=In({auth:null,user:null,loggedin:!1}),OO=(t={})=>{const e=Array.isArray(t==null?void 0:t.attendance_spots)?t.attendance_spots:[];if(e.length)return e.map(s=>({id:(s==null?void 0:s.id)??null,name:(s==null?void 0:s.name)??"Spot",latitude:Number(s==null?void 0:s.latitude),longitude:Number(s==null?void 0:s.longitude),radius_meters:Number(s==null?void 0:s.radius_meters)})).filter(s=>Number.isFinite(s.latitude)&&Number.isFinite(s.longitude)&&Number.isFinite(s.radius_meters)&&s.radius_meters>0);const n=t==null?void 0:t.attendance_location;return n&&Number.isFinite(Number(n==null?void 0:n.latitude))&&Number.isFinite(Number(n==null?void 0:n.longitude))&&Number.isFinite(Number(n==null?void 0:n.radius_meters))&&Number(n==null?void 0:n.radius_meters)>0?[{id:null,name:"Default Spot",latitude:Number(n.latitude),longitude:Number(n.longitude),radius_meters:Number(n.radius_meters)}]:[]},ub=(t={})=>({...t,attendance_spots:OO(t)}),NO=In({isLoading:!1,installed:!1}),_H=(t=!0)=>{NO.isLoading=t},Vd=()=>typeof window>"u"?null:localStorage.getItem(Ch),Wd=t=>{typeof window>"u"||(t?localStorage.setItem(Ch,t):localStorage.removeItem(Ch))},xO=async()=>{const t=Vd();if(!t)return ze.auth=null,ze.user=null,ze.loggedin=!1,ze;ze.auth={accessToken:t};const e=await je("auth_me");return!e||(e==null?void 0:e.message)==="Unauthorized"?(Wd(null),ze.auth=null,ze.user=null,ze.loggedin=!1,ze):(ze.user=ub((e==null?void 0:e.employee)??{}),ze.loggedin=!0,ze)},Eu=()=>{Vd()&&je("auth_logout").catch(()=>null),Wd(null),ze.auth=null,ze.user=null,ze.loggedin=!1},je=async(t,e={})=>{var o;const n=Q1[t],s={};let i="",r="";if(s.headers={},e.params&&(i=Object.entries(e.params).map(([l,c])=>Array.isArray(c)?c.map(h=>`${l}[]=${h}`).join("&"):`${l}=${c}`).join("&"),i=i?`?${i}`:""),n.auth){const l=Vd()??((o=ze.auth)==null?void 0:o.accessToken);l&&(s.headers.Authorization=`Bearer ${l}`)}if(e.body instanceof FormData)s.body=e.body;else if(e.body instanceof File){const l=new FormData,c=e.upload??"file";l.append(c,e.body),s.body=l}else e.body&&(s.headers["Content-Type"]="application/json",s.body=JSON.stringify(e.body));e.route&&(r=`/${e.route}`);try{const l=await fetch(`${Z1}${n.url}${r}${i}`,{method:n.method,...s}),c=await l.json();if(!l.ok){if(l.status===401){Eu(),Vw("/auth");return}throw{status:l.status,data:c}}if(c.code===1012||c.title==="Unauthorized"||c.message==="Unauthorized"){Eu(),setTimeout(()=>{location.reload()},1e3);return}return c}catch(l){if(l!=null&&l.response)try{(await l.response.text()).includes("<html")&&(Eu(),location.reload())}catch{}throw l}},yH=(t,e=!1)=>{const n=new Date(t),s=n.getFullYear(),i=(n.getMonth()+1).toString().padStart(2,"0"),r=n.getDate().toString().padStart(2,"0"),o=n.getHours().toString().padStart(2,"0"),l=n.getMinutes().toString().padStart(2,"0"),c=n.getSeconds().toString().padStart(2,"0");return e?{hours:o,minutes:l,seconds:c}:`${s}-${i}-${r} ${o}:${l}:${c}`},wH=async(t,e)=>{const n=await je("auth_login",{body:{email:t,password:e}});return!(n!=null&&n.token)||!(n!=null&&n.employee)?{ok:!1,data:n}:(Wd(n.token),ze.auth={accessToken:n.token},ze.user=ub(n.employee??{}),ze.loggedin=!0,{ok:!0,data:n})},vH=async()=>await je("faq",{params:{join:"faqs"}}),bH=async t=>await je("upload",{body:t}),hb=t=>t==="submitted"?"draft":t,DO=t=>t==="draft"?"submitted":t,db=t=>({id:t.id,employee_id:t.employee_id,date:t.date,hours:Number(t.hours??0),is_holiday:!!t.is_holiday,calculated_amount:Number(t.calculated_amount??0),status:DO(t.status),reason:t.reason??null,created_at:t.created_at,updated_at:t.updated_at}),fb=t=>({date:t.date,hours:Number(t.hours??t.duration??0),is_holiday:!!(t.is_holiday??!1),reason:t.reason??t.description??null,status:hb(t.status??"submitted")}),EH=async t=>{const e={...t,status:hb(t==null?void 0:t.status)},n=await je("get_overtimes",{params:e});return{...n,records:((n==null?void 0:n.records)??[]).map(db)}},TH=async t=>{const e=await je("get_overtimes",{route:t});return db(e)},CH=async t=>await je("set_overtime",{body:fb(t)}),IH=async(t,e)=>await je("update_overtime",{body:fb(e),route:t}),SH=async t=>({id:null,user_id:t,quota:12,taken:0,balance:12}),AH=async()=>{try{const t=await je("get_holidays");return((t==null?void 0:t.records)??[]).map(e=>({date:e.date??e.holiday_date,name:e.name??e.description}))}catch{return[]}},pb=t=>t==="submitted"?"pending":t,LO=t=>t==="pending"?"submitted":t,Ih=t=>!t||typeof t!="string"?t:t.replace(/^\/?storage\//,""),gb=t=>{var s;const e=t.start??`${t.start_date} 00:00:00`,n=t.end??`${t.end_date} 00:00:00`;return{id:t.id,type:"absence",sub_type:t.type??"others",status:LO(t.status),description:t.reason??null,reason:t.reason??null,duration:Number(t.duration??1),duration_um:"days",start:e,end:n,attachment:t.attachment_path,user_id:{id:t.employee_id,fullname:(s=ze.user)==null?void 0:s.fullname},created_at:t.created_at}},mb=t=>({type:t.sub_type??t.type??"others",start_date:(t.start||"").slice(0,10),end_date:(t.end||t.start||"").slice(0,10),reason:t.description??t.reason??null,attachment_path:Ih(t.attachment)??null,status:pb(t.status??"submitted")}),MO=t=>!t||t==="submitted"||t==="approved"?"present":t==="rejected"?"absent":t,Sh=t=>t==="present"||t==="late"||t==="permit"||t==="leave"?"approved":t==="absent"?"rejected":"submitted",_b=t=>{var s;const e=!!t.check_in,n=t.check_in??t.check_out;return{id:t.id,type:e?"in":"out",datetime:n,date:t.date,note:e?t.notes_in??t.notes:t.notes_out??t.notes,status:Sh(t.status),attachment:e?t.photo_in_path:t.photo_out_path,location:JSON.stringify({latitude:e?t.lat_in:t.lat_out,longitude:e?t.lng_in:t.lng_out}),user_id:{id:t.employee_id,fullname:(s=ze.user)==null?void 0:s.fullname}}},FO=t=>{var s;const e={id:t.employee_id,fullname:(s=ze.user)==null?void 0:s.fullname},n=[];return t.check_in&&n.push({id:t.id,type:"in",datetime:t.check_in,date:t.date,note:t.notes_in??t.notes,status:Sh(t.status),attachment:t.photo_in_path,location:JSON.stringify({latitude:t.lat_in,longitude:t.lng_in}),user_id:e}),t.check_out&&n.push({id:t.id,type:"out",datetime:t.check_out,date:t.date,note:t.notes_out??t.notes,status:Sh(t.status),attachment:t.photo_out_path,location:JSON.stringify({latitude:t.lat_out,longitude:t.lng_out}),user_id:e}),n.length||n.push(_b(t)),n},yb=t=>{const e=t.type==="in";let n={latitude:t.latitude,longitude:t.longitude};if(t.location)if(typeof t.location=="string")try{n=JSON.parse(t.location)}catch{n={latitude:t.latitude,longitude:t.longitude}}else n=t.location;return{date:(t.date||t.datetime||"").slice(0,10),check_in:e?t.datetime:null,check_out:e?null:t.datetime,lat_in:e?(n==null?void 0:n.latitude)??null:null,lng_in:e?(n==null?void 0:n.longitude)??null:null,lat_out:e?null:(n==null?void 0:n.latitude)??null,lng_out:e?null:(n==null?void 0:n.longitude)??null,status:MO(t.status),photo_in_path:e?Ih(t.attachment)??null:null,photo_out_path:e?null:Ih(t.attachment)??null,notes_in:e?t.note??null:null,notes_out:e?null:t.note??null,notes:t.note??null}},kH=async t=>await je("set_form",{body:mb(t)}),RH=async t=>{const e={...t,status:pb(t==null?void 0:t.status)},n=await je("get_forms",{params:e});return{...n,records:((n==null?void 0:n.records)??[]).map(gb)}},PH=async t=>{const e=await je("get_dinas_clocks",{params:t});return{...e,records:((e==null?void 0:e.records)??[]).flatMap(FO)}},OH=async t=>{const e=await je("get_forms",{route:t});return gb(e)},NH=async t=>{const e=await je("get_dinas_clocks",{route:t});return _b(e)},xH=async(t,e)=>await je("update_form",{body:mb(e),route:t}),DH=async t=>await je("post_manual",{body:yb(t)}),LH=async(t,e)=>{const n=yb(t),s=new FormData;return Object.entries(n).forEach(([i,r])=>{r!=null&&r!==""&&s.append(i,String(r))}),e&&s.append("photo",e),await je("post_manual",{body:s})},MH=async(t,e)=>await je("set_token",{body:{user_id:t,token:e}}),FH=async t=>{var n;const e=await je("get_tokens",{route:`?filter=user_id,eq,${t}`});return(n=e==null?void 0:e.records)!=null&&n.length&&e.records.forEach(async s=>{await je("delete_token",{route:s.id})}),!0},UH=async t=>await je("get_tokens",{route:`?filter=user_id,eq,${t}`}),$H=async(t,e,n)=>await je("get_notif",{route:`?filter=user_id,eq,${t}&filter=notif_type,eq,${n}&order=created_at,desc&page=${e}`}),HH=async t=>await je("update_notif",{body:{readed_at:new Date().toISOString().replace("T"," ").replace("Z","")},route:t.join(",")}),UO=async(t,e)=>{if(t.path.startsWith("/auth"))return;if(await xO(),!ze.loggedin)return Vw({path:"/auth",query:{redirect:t.fullPath}})},$O=async t=>{let e,n;const s=([e,n]=Qi(()=>Nd(t.path)),e=await e,n(),e);if(s.redirect)return Ti(s.redirect,{acceptRelative:!0})?(window.location.href=s.redirect,!1):s.redirect},HO=[J1,UO,$O],ro={auth:()=>qe(()=>import("./auth-Cb5SoK5q.js"),[],import.meta.url)};function BO(t,e,n){const{pathname:s,search:i,hash:r}=e,o=t.indexOf("#");if(o>-1){const u=r.includes(t.slice(o))?t.slice(o).length:1;let h=r.slice(u);return h[0]!=="/"&&(h="/"+h),dg(h,"")}const l=dg(s,t),c=!n||zS(l,n,{trailingSlash:!0})?l:n;return c+(c.includes("?")?"":i)+r}const jO=Rt({name:"nuxt:router",enforce:"pre",async setup(t){var D;let e,n,s=Mo().app.baseURL;on.hashMode&&!s.includes("#")&&(s+="#");const i=((D=on.history)==null?void 0:D.call(on,s))??(on.hashMode?y1(s):Ov(s)),r=on.routes?([e,n]=Qi(()=>on.routes(mu)),e=await e,n(),e??mu):mu;let o;const l=B1({...on,scrollBehavior:(M,x,b)=>{if(x===gn){o=b;return}if(on.scrollBehavior){if(l.options.scrollBehavior=on.scrollBehavior,"scrollRestoration"in window.history){const R=l.beforeEach(()=>{R(),window.history.scrollRestoration="manual"})}return on.scrollBehavior(M,gn,o||b)}},history:i,routes:r});"scrollRestoration"in window.history&&(window.history.scrollRestoration="auto"),t.vueApp.use(l);const c=go(l.currentRoute.value);l.afterEach((M,x)=>{c.value=x}),Object.defineProperty(t.vueApp.config.globalProperties,"previousRoute",{get:()=>c.value});const u=BO(s,window.location,t.payload.path),h=go(l.currentRoute.value),f=()=>{h.value=l.currentRoute.value};t.hook("page:finish",f),l.afterEach((M,x)=>{var b,R,N,F;((R=(b=M.matched[0])==null?void 0:b.components)==null?void 0:R.default)===((F=(N=x.matched[0])==null?void 0:N.components)==null?void 0:F.default)&&f()});const g={};for(const M in h.value)Object.defineProperty(g,M,{get:()=>h.value[M],enumerable:!0});t._route=Gn(g),t._middleware=t._middleware||{global:[],named:{}};const m=nc();l.afterEach(async(M,x,b)=>{delete t._processingMiddleware,!t.isHydrating&&m.value&&await t.runWithContext(qA),b&&await t.callHook("page:loading:end"),M.matched.length===0&&await t.runWithContext(()=>ji(oh({statusCode:404,fatal:!1,statusMessage:`Page not found: ${M.fullPath}`,data:{path:M.fullPath}})))});try{[e,n]=Qi(()=>l.isReady()),await e,n()}catch(M){[e,n]=Qi(()=>t.runWithContext(()=>ji(M))),await e,n()}const I=u!==l.currentRoute.value.fullPath?l.resolve(u):l.currentRoute.value;f();const P=t.payload.state._layout;return l.beforeEach(async(M,x)=>{var b;await t.callHook("page:loading:start"),M.meta=In(M.meta),t.isHydrating&&P&&!Ms(M.meta.layout)&&(M.meta.layout=P),t._processingMiddleware=!0;{const R=new Set([...HO,...t._middleware.global]);for(const N of M.matched){const F=N.meta.middleware;if(F)for(const T of Ld(F))R.add(T)}{const N=await t.runWithContext(()=>Nd(M.path));if(N.appMiddleware)for(const F in N.appMiddleware)N.appMiddleware[F]?R.add(F):R.delete(F)}for(const N of R){const F=typeof N=="string"?t._middleware.named[N]||await((b=ro[N])==null?void 0:b.call(ro).then(w=>w.default||w)):N;if(!F)throw new Error(`Unknown route middleware: '${N}'.`);const T=await t.runWithContext(()=>F(M,x));if(!t.payload.serverRendered&&t.isHydrating&&(T===!1||T instanceof Error)){const w=T||oh({statusCode:404,statusMessage:`Page Not Found: ${u}`});return await t.runWithContext(()=>ji(w)),!1}if(T!==!0&&(T||T===!1))return T}}}),l.onError(async()=>{delete t._processingMiddleware,await t.callHook("page:loading:end")}),t.hooks.hookOnce("app:created",async()=>{try{"name"in I&&(I.name=void 0),await l.replace({...I,force:!0}),l.options.scrollBehavior=on.scrollBehavior}catch(M){await t.runWithContext(()=>ji(M))}}),{provide:{router:l}}}}),ym=globalThis.requestIdleCallback||(t=>{const e=Date.now(),n={didTimeout:!1,timeRemaining:()=>Math.max(0,50-(Date.now()-e))};return setTimeout(()=>{t(n)},1)}),BH=globalThis.cancelIdleCallback||(t=>{clearTimeout(t)}),Kd=t=>{const e=Je();e.isHydrating?e.hooks.hookOnce("app:suspense:resolve",()=>{ym(()=>t())}):ym(()=>t())},VO=Rt({name:"nuxt:payload",setup(t){hn().beforeResolve(async(e,n)=>{if(e.path===n.path)return;const s=await jg(e.path);s&&Object.assign(t.static.data,s.data)}),Kd(()=>{var e;t.hooks.hook("link:prefetch",async n=>{const{hostname:s}=new URL(n,window.location.href);s===window.location.hostname&&await jg(n)}),((e=navigator.connection)==null?void 0:e.effectiveType)!=="slow-2g"&&setTimeout(lc,1e3)})}}),WO=Rt(()=>{const t=hn();Kd(()=>{t.beforeResolve(async()=>{await new Promise(e=>{setTimeout(e,100),requestAnimationFrame(()=>{setTimeout(e,0)})})})})}),KO=Rt(t=>{let e;async function n(){const s=await lc();e&&clearTimeout(e),e=setTimeout(n,_g);try{const i=await $fetch(Ed("builds/latest.json")+`?${Date.now()}`);i.id!==s.id&&t.hooks.callHook("app:manifest:update",i)}catch{}}Kd(()=>{e=setTimeout(n,_g)})});function qO(t={}){const e=t.path||window.location.pathname;let n={};try{n=rl(sessionStorage.getItem("nuxt:reload")||"{}")}catch{}if(t.force||(n==null?void 0:n.path)!==e||(n==null?void 0:n.expires)<Date.now()){try{sessionStorage.setItem("nuxt:reload",JSON.stringify({path:e,expires:Date.now()+(t.ttl??1e4)}))}catch{}if(t.persistState)try{sessionStorage.setItem("nuxt:reload:state",JSON.stringify({state:Je().payload.state}))}catch{}window.location.pathname!==e?window.location.href=e:window.location.reload()}}const zO=Rt({name:"nuxt:chunk-reload",setup(t){const e=hn(),n=Mo(),s=new Set;e.beforeEach(()=>{s.clear()}),t.hook("app:chunkError",({error:r})=>{s.add(r)});function i(r){const l="href"in r&&r.href[0]==="#"?n.app.baseURL+r.href:bd(n.app.baseURL,r.fullPath);qO({path:l,persistState:!0})}t.hook("app:manifest:update",()=>{e.beforeResolve(i)}),e.onError((r,o)=>{s.has(r)&&i(o)})}}),GO=Rt({name:"nuxt:global-components"}),Ps={},YO=Rt({name:"nuxt:prefetch",setup(t){const e=hn();t.hooks.hook("app:mounted",()=>{e.beforeEach(async n=>{var i;const s=(i=n==null?void 0:n.meta)==null?void 0:i.layout;s&&typeof Ps[s]=="function"&&await Ps[s]()})}),t.hooks.hook("link:prefetch",n=>{if(Ti(n))return;const s=e.resolve(n);if(!s)return;const i=s.meta.layout;let r=Ld(s.meta.middleware);r=r.filter(o=>typeof o=="string");for(const o of r)typeof ro[o]=="function"&&ro[o]();i&&typeof Ps[i]=="function"&&Ps[i]()})}}),XO=Rt(()=>({provide:{firebaseApp:kd(Mo().public.vuefire.config)}}));function qd(t,e){var n={};for(var s in t)Object.prototype.hasOwnProperty.call(t,s)&&e.indexOf(s)<0&&(n[s]=t[s]);if(t!=null&&typeof Object.getOwnPropertySymbols=="function")for(var i=0,s=Object.getOwnPropertySymbols(t);i<s.length;i++)e.indexOf(s[i])<0&&Object.prototype.propertyIsEnumerable.call(t,s[i])&&(n[s[i]]=t[s[i]]);return n}function wb(){return{"dependent-sdk-initialized-before-auth":"Another Firebase SDK was initialized and is trying to use Auth before Auth is initialized. Please be sure to call `initializeAuth` or `getAuth` before starting any other Firebase SDK."}}const vb=wb,bb=new Bs("auth","Firebase",wb());/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const bl=new Uo("@firebase/auth");function JO(t,...e){bl.logLevel<=Ie.WARN&&bl.warn(`Auth (${js}): ${t}`,...e)}function Ka(t,...e){bl.logLevel<=Ie.ERROR&&bl.error(`Auth (${js}): ${t}`,...e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function rs(t,...e){throw zd(t,...e)}function xn(t,...e){return zd(t,...e)}function Eb(t,e,n){const s=Object.assign(Object.assign({},vb()),{[e]:n});return new Bs("auth","Firebase",s).create(e,{appName:t.name})}function ui(t){return Eb(t,"operation-not-supported-in-this-environment","Operations that alter the current user are not supported in conjunction with FirebaseServerApp")}function zd(t,...e){if(typeof t!="string"){const n=e[0],s=[...e.slice(1)];return s[0]&&(s[0].appName=t.name),t._errorFactory.create(n,...s)}return bb.create(t,...e)}function ue(t,e,...n){if(!t)throw zd(e,...n)}function Yn(t){const e="INTERNAL ASSERTION FAILED: "+t;throw Ka(e),new Error(e)}function os(t,e){t||Yn(e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Ah(){var t;return typeof self<"u"&&((t=self.location)===null||t===void 0?void 0:t.href)||""}function QO(){return wm()==="http:"||wm()==="https:"}function wm(){var t;return typeof self<"u"&&((t=self.location)===null||t===void 0?void 0:t.protocol)||null}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function ZO(){return typeof navigator<"u"&&navigator&&"onLine"in navigator&&typeof navigator.onLine=="boolean"&&(QO()||ik()||"connection"in navigator)?navigator.onLine:!0}function eN(){if(typeof navigator>"u")return null;const t=navigator;return t.languages&&t.languages[0]||t.language||null}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ho{constructor(e,n){this.shortDelay=e,this.longDelay=n,os(n>e,"Short delay should be less than long delay!"),this.isMobile=Id()||Qw()}get(){return ZO()?this.isMobile?this.longDelay:this.shortDelay:Math.min(5e3,this.shortDelay)}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Gd(t,e){os(t.emulator,"Emulator should always be set here");const{url:n}=t.emulator;return e?`${n}${e.startsWith("/")?e.slice(1):e}`:n}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Tb{static initialize(e,n,s){this.fetchImpl=e,n&&(this.headersImpl=n),s&&(this.responseImpl=s)}static fetch(){if(this.fetchImpl)return this.fetchImpl;if(typeof self<"u"&&"fetch"in self)return self.fetch;if(typeof globalThis<"u"&&globalThis.fetch)return globalThis.fetch;if(typeof fetch<"u")return fetch;Yn("Could not find fetch implementation, make sure you call FetchProvider.initialize() with an appropriate polyfill")}static headers(){if(this.headersImpl)return this.headersImpl;if(typeof self<"u"&&"Headers"in self)return self.Headers;if(typeof globalThis<"u"&&globalThis.Headers)return globalThis.Headers;if(typeof Headers<"u")return Headers;Yn("Could not find Headers implementation, make sure you call FetchProvider.initialize() with an appropriate polyfill")}static response(){if(this.responseImpl)return this.responseImpl;if(typeof self<"u"&&"Response"in self)return self.Response;if(typeof globalThis<"u"&&globalThis.Response)return globalThis.Response;if(typeof Response<"u")return Response;Yn("Could not find Response implementation, make sure you call FetchProvider.initialize() with an appropriate polyfill")}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const tN={CREDENTIAL_MISMATCH:"custom-token-mismatch",MISSING_CUSTOM_TOKEN:"internal-error",INVALID_IDENTIFIER:"invalid-email",MISSING_CONTINUE_URI:"internal-error",INVALID_PASSWORD:"wrong-password",MISSING_PASSWORD:"missing-password",INVALID_LOGIN_CREDENTIALS:"invalid-credential",EMAIL_EXISTS:"email-already-in-use",PASSWORD_LOGIN_DISABLED:"operation-not-allowed",INVALID_IDP_RESPONSE:"invalid-credential",INVALID_PENDING_TOKEN:"invalid-credential",FEDERATED_USER_ID_ALREADY_LINKED:"credential-already-in-use",MISSING_REQ_TYPE:"internal-error",EMAIL_NOT_FOUND:"user-not-found",RESET_PASSWORD_EXCEED_LIMIT:"too-many-requests",EXPIRED_OOB_CODE:"expired-action-code",INVALID_OOB_CODE:"invalid-action-code",MISSING_OOB_CODE:"internal-error",CREDENTIAL_TOO_OLD_LOGIN_AGAIN:"requires-recent-login",INVALID_ID_TOKEN:"invalid-user-token",TOKEN_EXPIRED:"user-token-expired",USER_NOT_FOUND:"user-token-expired",TOO_MANY_ATTEMPTS_TRY_LATER:"too-many-requests",PASSWORD_DOES_NOT_MEET_REQUIREMENTS:"password-does-not-meet-requirements",INVALID_CODE:"invalid-verification-code",INVALID_SESSION_INFO:"invalid-verification-id",INVALID_TEMPORARY_PROOF:"invalid-credential",MISSING_SESSION_INFO:"missing-verification-id",SESSION_EXPIRED:"code-expired",MISSING_ANDROID_PACKAGE_NAME:"missing-android-pkg-name",UNAUTHORIZED_DOMAIN:"unauthorized-continue-uri",INVALID_OAUTH_CLIENT_ID:"invalid-oauth-client-id",ADMIN_ONLY_OPERATION:"admin-restricted-operation",INVALID_MFA_PENDING_CREDENTIAL:"invalid-multi-factor-session",MFA_ENROLLMENT_NOT_FOUND:"multi-factor-info-not-found",MISSING_MFA_ENROLLMENT_ID:"missing-multi-factor-info",MISSING_MFA_PENDING_CREDENTIAL:"missing-multi-factor-session",SECOND_FACTOR_EXISTS:"second-factor-already-in-use",SECOND_FACTOR_LIMIT_EXCEEDED:"maximum-second-factor-count-exceeded",BLOCKING_FUNCTION_ERROR_RESPONSE:"internal-error",RECAPTCHA_NOT_ENABLED:"recaptcha-not-enabled",MISSING_RECAPTCHA_TOKEN:"missing-recaptcha-token",INVALID_RECAPTCHA_TOKEN:"invalid-recaptcha-token",INVALID_RECAPTCHA_ACTION:"invalid-recaptcha-action",MISSING_CLIENT_TYPE:"missing-client-type",MISSING_RECAPTCHA_VERSION:"missing-recaptcha-version",INVALID_RECAPTCHA_VERSION:"invalid-recaptcha-version",INVALID_REQ_TYPE:"invalid-req-type"};/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const nN=new Ho(3e4,6e4);function Bo(t,e){return t.tenantId&&!e.tenantId?Object.assign(Object.assign({},e),{tenantId:t.tenantId}):e}async function Vs(t,e,n,s,i={}){return Cb(t,i,async()=>{let r={},o={};s&&(e==="GET"?o=s:r={body:JSON.stringify(s)});const l=vr(Object.assign({key:t.config.apiKey},o)).slice(1),c=await t._getAdditionalHeaders();c["Content-Type"]="application/json",t.languageCode&&(c["X-Firebase-Locale"]=t.languageCode);const u=Object.assign({method:e,headers:c},r);return sk()||(u.referrerPolicy="no-referrer"),Tb.fetch()(Ib(t,t.config.apiHost,n,l),u)})}async function Cb(t,e,n){t._canInitEmulator=!1;const s=Object.assign(Object.assign({},tN),e);try{const i=new rN(t),r=await Promise.race([n(),i.promise]);i.clearNetworkTimeout();const o=await r.json();if("needConfirmation"in o)throw Pa(t,"account-exists-with-different-credential",o);if(r.ok&&!("errorMessage"in o))return o;{const l=r.ok?o.errorMessage:o.error.message,[c,u]=l.split(" : ");if(c==="FEDERATED_USER_ID_ALREADY_LINKED")throw Pa(t,"credential-already-in-use",o);if(c==="EMAIL_EXISTS")throw Pa(t,"email-already-in-use",o);if(c==="USER_DISABLED")throw Pa(t,"user-disabled",o);const h=s[c]||c.toLowerCase().replace(/[_\s]+/g,"-");if(u)throw Eb(t,h,u);rs(t,h)}}catch(i){if(i instanceof Sn)throw i;rs(t,"network-request-failed",{message:String(i)})}}async function sN(t,e,n,s,i={}){const r=await Vs(t,e,n,s,i);return"mfaPendingCredential"in r&&rs(t,"multi-factor-auth-required",{_serverResponse:r}),r}function Ib(t,e,n,s){const i=`${e}${n}?${s}`;return t.config.emulator?Gd(t.config,i):`${t.config.apiScheme}://${i}`}function iN(t){switch(t){case"ENFORCE":return"ENFORCE";case"AUDIT":return"AUDIT";case"OFF":return"OFF";default:return"ENFORCEMENT_STATE_UNSPECIFIED"}}class rN{constructor(e){this.auth=e,this.timer=null,this.promise=new Promise((n,s)=>{this.timer=setTimeout(()=>s(xn(this.auth,"network-request-failed")),nN.get())})}clearNetworkTimeout(){clearTimeout(this.timer)}}function Pa(t,e,n){const s={appName:t.name};n.email&&(s.email=n.email),n.phoneNumber&&(s.phoneNumber=n.phoneNumber);const i=xn(t,e,s);return i.customData._tokenResponse=n,i}function vm(t){return t!==void 0&&t.enterprise!==void 0}class oN{constructor(e){if(this.siteKey="",this.recaptchaEnforcementState=[],e.recaptchaKey===void 0)throw new Error("recaptchaKey undefined");this.siteKey=e.recaptchaKey.split("/")[3],this.recaptchaEnforcementState=e.recaptchaEnforcementState}getProviderEnforcementState(e){if(!this.recaptchaEnforcementState||this.recaptchaEnforcementState.length===0)return null;for(const n of this.recaptchaEnforcementState)if(n.provider&&n.provider===e)return iN(n.enforcementState);return null}isProviderEnabled(e){return this.getProviderEnforcementState(e)==="ENFORCE"||this.getProviderEnforcementState(e)==="AUDIT"}}async function aN(t,e){return Vs(t,"GET","/v2/recaptchaConfig",Bo(t,e))}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function lN(t,e){return Vs(t,"POST","/v1/accounts:delete",e)}async function Sb(t,e){return Vs(t,"POST","/v1/accounts:lookup",e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function oo(t){if(t)try{const e=new Date(Number(t));if(!isNaN(e.getTime()))return e.toUTCString()}catch{}}async function cN(t,e=!1){const n=Un(t),s=await n.getIdToken(e),i=Yd(s);ue(i&&i.exp&&i.auth_time&&i.iat,n.auth,"internal-error");const r=typeof i.firebase=="object"?i.firebase:void 0,o=r==null?void 0:r.sign_in_provider;return{claims:i,token:s,authTime:oo(Tu(i.auth_time)),issuedAtTime:oo(Tu(i.iat)),expirationTime:oo(Tu(i.exp)),signInProvider:o||null,signInSecondFactor:(r==null?void 0:r.sign_in_second_factor)||null}}function Tu(t){return Number(t)*1e3}function Yd(t){const[e,n,s]=t.split(".");if(e===void 0||n===void 0||s===void 0)return Ka("JWT malformed, contained fewer than 3 sections"),null;try{const i=ul(n);return i?JSON.parse(i):(Ka("Failed to decode base64 JWT payload"),null)}catch(i){return Ka("Caught error parsing JWT payload as JSON",i==null?void 0:i.toString()),null}}function bm(t){const e=Yd(t);return ue(e,"internal-error"),ue(typeof e.exp<"u","internal-error"),ue(typeof e.iat<"u","internal-error"),Number(e.exp)-Number(e.iat)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Ao(t,e,n=!1){if(n)return e;try{return await e}catch(s){throw s instanceof Sn&&uN(s)&&t.auth.currentUser===t&&await t.auth.signOut(),s}}function uN({code:t}){return t==="auth/user-disabled"||t==="auth/user-token-expired"}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class hN{constructor(e){this.user=e,this.isRunning=!1,this.timerId=null,this.errorBackoff=3e4}_start(){this.isRunning||(this.isRunning=!0,this.schedule())}_stop(){this.isRunning&&(this.isRunning=!1,this.timerId!==null&&clearTimeout(this.timerId))}getInterval(e){var n;if(e){const s=this.errorBackoff;return this.errorBackoff=Math.min(this.errorBackoff*2,96e4),s}else{this.errorBackoff=3e4;const i=((n=this.user.stsTokenManager.expirationTime)!==null&&n!==void 0?n:0)-Date.now()-3e5;return Math.max(0,i)}}schedule(e=!1){if(!this.isRunning)return;const n=this.getInterval(e);this.timerId=setTimeout(async()=>{await this.iteration()},n)}async iteration(){try{await this.user.getIdToken(!0)}catch(e){(e==null?void 0:e.code)==="auth/network-request-failed"&&this.schedule(!0);return}this.schedule()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class kh{constructor(e,n){this.createdAt=e,this.lastLoginAt=n,this._initializeTime()}_initializeTime(){this.lastSignInTime=oo(this.lastLoginAt),this.creationTime=oo(this.createdAt)}_copy(e){this.createdAt=e.createdAt,this.lastLoginAt=e.lastLoginAt,this._initializeTime()}toJSON(){return{createdAt:this.createdAt,lastLoginAt:this.lastLoginAt}}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function El(t){var e;const n=t.auth,s=await t.getIdToken(),i=await Ao(t,Sb(n,{idToken:s}));ue(i==null?void 0:i.users.length,n,"internal-error");const r=i.users[0];t._notifyReloadListener(r);const o=!((e=r.providerUserInfo)===null||e===void 0)&&e.length?Ab(r.providerUserInfo):[],l=fN(t.providerData,o),c=t.isAnonymous,u=!(t.email&&r.passwordHash)&&!(l!=null&&l.length),h=c?u:!1,f={uid:r.localId,displayName:r.displayName||null,photoURL:r.photoUrl||null,email:r.email||null,emailVerified:r.emailVerified||!1,phoneNumber:r.phoneNumber||null,tenantId:r.tenantId||null,providerData:l,metadata:new kh(r.createdAt,r.lastLoginAt),isAnonymous:h};Object.assign(t,f)}async function dN(t){const e=Un(t);await El(e),await e.auth._persistUserIfCurrent(e),e.auth._notifyListenersIfCurrent(e)}function fN(t,e){return[...t.filter(s=>!e.some(i=>i.providerId===s.providerId)),...e]}function Ab(t){return t.map(e=>{var{providerId:n}=e,s=qd(e,["providerId"]);return{providerId:n,uid:s.rawId||"",displayName:s.displayName||null,email:s.email||null,phoneNumber:s.phoneNumber||null,photoURL:s.photoUrl||null}})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function pN(t,e){const n=await Cb(t,{},async()=>{const s=vr({grant_type:"refresh_token",refresh_token:e}).slice(1),{tokenApiHost:i,apiKey:r}=t.config,o=Ib(t,i,"/v1/token",`key=${r}`),l=await t._getAdditionalHeaders();return l["Content-Type"]="application/x-www-form-urlencoded",Tb.fetch()(o,{method:"POST",headers:l,body:s})});return{accessToken:n.access_token,expiresIn:n.expires_in,refreshToken:n.refresh_token}}async function gN(t,e){return Vs(t,"POST","/v2/accounts:revokeToken",Bo(t,e))}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class er{constructor(){this.refreshToken=null,this.accessToken=null,this.expirationTime=null}get isExpired(){return!this.expirationTime||Date.now()>this.expirationTime-3e4}updateFromServerResponse(e){ue(e.idToken,"internal-error"),ue(typeof e.idToken<"u","internal-error"),ue(typeof e.refreshToken<"u","internal-error");const n="expiresIn"in e&&typeof e.expiresIn<"u"?Number(e.expiresIn):bm(e.idToken);this.updateTokensAndExpiration(e.idToken,e.refreshToken,n)}updateFromIdToken(e){ue(e.length!==0,"internal-error");const n=bm(e);this.updateTokensAndExpiration(e,null,n)}async getToken(e,n=!1){return!n&&this.accessToken&&!this.isExpired?this.accessToken:(ue(this.refreshToken,e,"user-token-expired"),this.refreshToken?(await this.refresh(e,this.refreshToken),this.accessToken):null)}clearRefreshToken(){this.refreshToken=null}async refresh(e,n){const{accessToken:s,refreshToken:i,expiresIn:r}=await pN(e,n);this.updateTokensAndExpiration(s,i,Number(r))}updateTokensAndExpiration(e,n,s){this.refreshToken=n||null,this.accessToken=e||null,this.expirationTime=Date.now()+s*1e3}static fromJSON(e,n){const{refreshToken:s,accessToken:i,expirationTime:r}=n,o=new er;return s&&(ue(typeof s=="string","internal-error",{appName:e}),o.refreshToken=s),i&&(ue(typeof i=="string","internal-error",{appName:e}),o.accessToken=i),r&&(ue(typeof r=="number","internal-error",{appName:e}),o.expirationTime=r),o}toJSON(){return{refreshToken:this.refreshToken,accessToken:this.accessToken,expirationTime:this.expirationTime}}_assign(e){this.accessToken=e.accessToken,this.refreshToken=e.refreshToken,this.expirationTime=e.expirationTime}_clone(){return Object.assign(new er,this.toJSON())}_performRefresh(){return Yn("not implemented")}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function ws(t,e){ue(typeof t=="string"||typeof t>"u","internal-error",{appName:e})}class Xn{constructor(e){var{uid:n,auth:s,stsTokenManager:i}=e,r=qd(e,["uid","auth","stsTokenManager"]);this.providerId="firebase",this.proactiveRefresh=new hN(this),this.reloadUserInfo=null,this.reloadListener=null,this.uid=n,this.auth=s,this.stsTokenManager=i,this.accessToken=i.accessToken,this.displayName=r.displayName||null,this.email=r.email||null,this.emailVerified=r.emailVerified||!1,this.phoneNumber=r.phoneNumber||null,this.photoURL=r.photoURL||null,this.isAnonymous=r.isAnonymous||!1,this.tenantId=r.tenantId||null,this.providerData=r.providerData?[...r.providerData]:[],this.metadata=new kh(r.createdAt||void 0,r.lastLoginAt||void 0)}async getIdToken(e){const n=await Ao(this,this.stsTokenManager.getToken(this.auth,e));return ue(n,this.auth,"internal-error"),this.accessToken!==n&&(this.accessToken=n,await this.auth._persistUserIfCurrent(this),this.auth._notifyListenersIfCurrent(this)),n}getIdTokenResult(e){return cN(this,e)}reload(){return dN(this)}_assign(e){this!==e&&(ue(this.uid===e.uid,this.auth,"internal-error"),this.displayName=e.displayName,this.photoURL=e.photoURL,this.email=e.email,this.emailVerified=e.emailVerified,this.phoneNumber=e.phoneNumber,this.isAnonymous=e.isAnonymous,this.tenantId=e.tenantId,this.providerData=e.providerData.map(n=>Object.assign({},n)),this.metadata._copy(e.metadata),this.stsTokenManager._assign(e.stsTokenManager))}_clone(e){const n=new Xn(Object.assign(Object.assign({},this),{auth:e,stsTokenManager:this.stsTokenManager._clone()}));return n.metadata._copy(this.metadata),n}_onReload(e){ue(!this.reloadListener,this.auth,"internal-error"),this.reloadListener=e,this.reloadUserInfo&&(this._notifyReloadListener(this.reloadUserInfo),this.reloadUserInfo=null)}_notifyReloadListener(e){this.reloadListener?this.reloadListener(e):this.reloadUserInfo=e}_startProactiveRefresh(){this.proactiveRefresh._start()}_stopProactiveRefresh(){this.proactiveRefresh._stop()}async _updateTokensIfNecessary(e,n=!1){let s=!1;e.idToken&&e.idToken!==this.stsTokenManager.accessToken&&(this.stsTokenManager.updateFromServerResponse(e),s=!0),n&&await El(this),await this.auth._persistUserIfCurrent(this),s&&this.auth._notifyListenersIfCurrent(this)}async delete(){if(Rs(this.auth.app))return Promise.reject(ui(this.auth));const e=await this.getIdToken();return await Ao(this,lN(this.auth,{idToken:e})),this.stsTokenManager.clearRefreshToken(),this.auth.signOut()}toJSON(){return Object.assign(Object.assign({uid:this.uid,email:this.email||void 0,emailVerified:this.emailVerified,displayName:this.displayName||void 0,isAnonymous:this.isAnonymous,photoURL:this.photoURL||void 0,phoneNumber:this.phoneNumber||void 0,tenantId:this.tenantId||void 0,providerData:this.providerData.map(e=>Object.assign({},e)),stsTokenManager:this.stsTokenManager.toJSON(),_redirectEventId:this._redirectEventId},this.metadata.toJSON()),{apiKey:this.auth.config.apiKey,appName:this.auth.name})}get refreshToken(){return this.stsTokenManager.refreshToken||""}static _fromJSON(e,n){var s,i,r,o,l,c,u,h;const f=(s=n.displayName)!==null&&s!==void 0?s:void 0,g=(i=n.email)!==null&&i!==void 0?i:void 0,m=(r=n.phoneNumber)!==null&&r!==void 0?r:void 0,I=(o=n.photoURL)!==null&&o!==void 0?o:void 0,P=(l=n.tenantId)!==null&&l!==void 0?l:void 0,D=(c=n._redirectEventId)!==null&&c!==void 0?c:void 0,M=(u=n.createdAt)!==null&&u!==void 0?u:void 0,x=(h=n.lastLoginAt)!==null&&h!==void 0?h:void 0,{uid:b,emailVerified:R,isAnonymous:N,providerData:F,stsTokenManager:T}=n;ue(b&&T,e,"internal-error");const w=er.fromJSON(this.name,T);ue(typeof b=="string",e,"internal-error"),ws(f,e.name),ws(g,e.name),ue(typeof R=="boolean",e,"internal-error"),ue(typeof N=="boolean",e,"internal-error"),ws(m,e.name),ws(I,e.name),ws(P,e.name),ws(D,e.name),ws(M,e.name),ws(x,e.name);const y=new Xn({uid:b,auth:e,email:g,emailVerified:R,displayName:f,isAnonymous:N,photoURL:I,phoneNumber:m,tenantId:P,stsTokenManager:w,createdAt:M,lastLoginAt:x});return F&&Array.isArray(F)&&(y.providerData=F.map(v=>Object.assign({},v))),D&&(y._redirectEventId=D),y}static async _fromIdTokenResponse(e,n,s=!1){const i=new er;i.updateFromServerResponse(n);const r=new Xn({uid:n.localId,auth:e,stsTokenManager:i,isAnonymous:s});return await El(r),r}static async _fromGetAccountInfoResponse(e,n,s){const i=n.users[0];ue(i.localId!==void 0,"internal-error");const r=i.providerUserInfo!==void 0?Ab(i.providerUserInfo):[],o=!(i.email&&i.passwordHash)&&!(r!=null&&r.length),l=new er;l.updateFromIdToken(s);const c=new Xn({uid:i.localId,auth:e,stsTokenManager:l,isAnonymous:o}),u={uid:i.localId,displayName:i.displayName||null,photoURL:i.photoUrl||null,email:i.email||null,emailVerified:i.emailVerified||!1,phoneNumber:i.phoneNumber||null,tenantId:i.tenantId||null,providerData:r,metadata:new kh(i.createdAt,i.lastLoginAt),isAnonymous:!(i.email&&i.passwordHash)&&!(r!=null&&r.length)};return Object.assign(c,u),c}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Em=new Map;function Jn(t){os(t instanceof Function,"Expected a class definition");let e=Em.get(t);return e?(os(e instanceof t,"Instance stored in cache mismatched with class"),e):(e=new t,Em.set(t,e),e)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class kb{constructor(){this.type="NONE",this.storage={}}async _isAvailable(){return!0}async _set(e,n){this.storage[e]=n}async _get(e){const n=this.storage[e];return n===void 0?null:n}async _remove(e){delete this.storage[e]}_addListener(e,n){}_removeListener(e,n){}}kb.type="NONE";const Tm=kb;/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function qa(t,e,n){return`firebase:${t}:${e}:${n}`}class tr{constructor(e,n,s){this.persistence=e,this.auth=n,this.userKey=s;const{config:i,name:r}=this.auth;this.fullUserKey=qa(this.userKey,i.apiKey,r),this.fullPersistenceKey=qa("persistence",i.apiKey,r),this.boundEventHandler=n._onStorageEvent.bind(n),this.persistence._addListener(this.fullUserKey,this.boundEventHandler)}setCurrentUser(e){return this.persistence._set(this.fullUserKey,e.toJSON())}async getCurrentUser(){const e=await this.persistence._get(this.fullUserKey);return e?Xn._fromJSON(this.auth,e):null}removeCurrentUser(){return this.persistence._remove(this.fullUserKey)}savePersistenceForRedirect(){return this.persistence._set(this.fullPersistenceKey,this.persistence.type)}async setPersistence(e){if(this.persistence===e)return;const n=await this.getCurrentUser();if(await this.removeCurrentUser(),this.persistence=e,n)return this.setCurrentUser(n)}delete(){this.persistence._removeListener(this.fullUserKey,this.boundEventHandler)}static async create(e,n,s="authUser"){if(!n.length)return new tr(Jn(Tm),e,s);const i=(await Promise.all(n.map(async u=>{if(await u._isAvailable())return u}))).filter(u=>u);let r=i[0]||Jn(Tm);const o=qa(s,e.config.apiKey,e.name);let l=null;for(const u of n)try{const h=await u._get(o);if(h){const f=Xn._fromJSON(e,h);u!==r&&(l=f),r=u;break}}catch{}const c=i.filter(u=>u._shouldAllowMigration);return!r._shouldAllowMigration||!c.length?new tr(r,e,s):(r=c[0],l&&await r._set(o,l.toJSON()),await Promise.all(n.map(async u=>{if(u!==r)try{await u._remove(o)}catch{}})),new tr(r,e,s))}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Cm(t){const e=t.toLowerCase();if(e.includes("opera/")||e.includes("opr/")||e.includes("opios/"))return"Opera";if(Nb(e))return"IEMobile";if(e.includes("msie")||e.includes("trident/"))return"IE";if(e.includes("edge/"))return"Edge";if(Rb(e))return"Firefox";if(e.includes("silk/"))return"Silk";if(Db(e))return"Blackberry";if(Lb(e))return"Webos";if(Pb(e))return"Safari";if((e.includes("chrome/")||Ob(e))&&!e.includes("edge/"))return"Chrome";if(xb(e))return"Android";{const n=/([a-zA-Z\d\.]+)\/[a-zA-Z\d\.]*$/,s=t.match(n);if((s==null?void 0:s.length)===2)return s[1]}return"Other"}function Rb(t=Lt()){return/firefox\//i.test(t)}function Pb(t=Lt()){const e=t.toLowerCase();return e.includes("safari/")&&!e.includes("chrome/")&&!e.includes("crios/")&&!e.includes("android")}function Ob(t=Lt()){return/crios\//i.test(t)}function Nb(t=Lt()){return/iemobile/i.test(t)}function xb(t=Lt()){return/android/i.test(t)}function Db(t=Lt()){return/blackberry/i.test(t)}function Lb(t=Lt()){return/webos/i.test(t)}function Xd(t=Lt()){return/iphone|ipad|ipod/i.test(t)||/macintosh/i.test(t)&&/mobile/i.test(t)}function mN(t=Lt()){var e;return Xd(t)&&!!(!((e=window.navigator)===null||e===void 0)&&e.standalone)}function _N(){return rk()&&document.documentMode===10}function Mb(t=Lt()){return Xd(t)||xb(t)||Lb(t)||Db(t)||/windows phone/i.test(t)||Nb(t)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Fb(t,e=[]){let n;switch(t){case"Browser":n=Cm(Lt());break;case"Worker":n=`${Cm(Lt())}-${t}`;break;default:n=t}const s=e.length?e.join(","):"FirebaseCore-web";return`${n}/JsCore/${js}/${s}`}/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class yN{constructor(e){this.auth=e,this.queue=[]}pushCallback(e,n){const s=r=>new Promise((o,l)=>{try{const c=e(r);o(c)}catch(c){l(c)}});s.onAbort=n,this.queue.push(s);const i=this.queue.length-1;return()=>{this.queue[i]=()=>Promise.resolve()}}async runMiddleware(e){if(this.auth.currentUser===e)return;const n=[];try{for(const s of this.queue)await s(e),s.onAbort&&n.push(s.onAbort)}catch(s){n.reverse();for(const i of n)try{i()}catch{}throw this.auth._errorFactory.create("login-blocked",{originalMessage:s==null?void 0:s.message})}}}/**
 * @license
 * Copyright 2023 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function wN(t,e={}){return Vs(t,"GET","/v2/passwordPolicy",Bo(t,e))}/**
 * @license
 * Copyright 2023 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const vN=6;class bN{constructor(e){var n,s,i,r;const o=e.customStrengthOptions;this.customStrengthOptions={},this.customStrengthOptions.minPasswordLength=(n=o.minPasswordLength)!==null&&n!==void 0?n:vN,o.maxPasswordLength&&(this.customStrengthOptions.maxPasswordLength=o.maxPasswordLength),o.containsLowercaseCharacter!==void 0&&(this.customStrengthOptions.containsLowercaseLetter=o.containsLowercaseCharacter),o.containsUppercaseCharacter!==void 0&&(this.customStrengthOptions.containsUppercaseLetter=o.containsUppercaseCharacter),o.containsNumericCharacter!==void 0&&(this.customStrengthOptions.containsNumericCharacter=o.containsNumericCharacter),o.containsNonAlphanumericCharacter!==void 0&&(this.customStrengthOptions.containsNonAlphanumericCharacter=o.containsNonAlphanumericCharacter),this.enforcementState=e.enforcementState,this.enforcementState==="ENFORCEMENT_STATE_UNSPECIFIED"&&(this.enforcementState="OFF"),this.allowedNonAlphanumericCharacters=(i=(s=e.allowedNonAlphanumericCharacters)===null||s===void 0?void 0:s.join(""))!==null&&i!==void 0?i:"",this.forceUpgradeOnSignin=(r=e.forceUpgradeOnSignin)!==null&&r!==void 0?r:!1,this.schemaVersion=e.schemaVersion}validatePassword(e){var n,s,i,r,o,l;const c={isValid:!0,passwordPolicy:this};return this.validatePasswordLengthOptions(e,c),this.validatePasswordCharacterOptions(e,c),c.isValid&&(c.isValid=(n=c.meetsMinPasswordLength)!==null&&n!==void 0?n:!0),c.isValid&&(c.isValid=(s=c.meetsMaxPasswordLength)!==null&&s!==void 0?s:!0),c.isValid&&(c.isValid=(i=c.containsLowercaseLetter)!==null&&i!==void 0?i:!0),c.isValid&&(c.isValid=(r=c.containsUppercaseLetter)!==null&&r!==void 0?r:!0),c.isValid&&(c.isValid=(o=c.containsNumericCharacter)!==null&&o!==void 0?o:!0),c.isValid&&(c.isValid=(l=c.containsNonAlphanumericCharacter)!==null&&l!==void 0?l:!0),c}validatePasswordLengthOptions(e,n){const s=this.customStrengthOptions.minPasswordLength,i=this.customStrengthOptions.maxPasswordLength;s&&(n.meetsMinPasswordLength=e.length>=s),i&&(n.meetsMaxPasswordLength=e.length<=i)}validatePasswordCharacterOptions(e,n){this.updatePasswordCharacterOptionsStatuses(n,!1,!1,!1,!1);let s;for(let i=0;i<e.length;i++)s=e.charAt(i),this.updatePasswordCharacterOptionsStatuses(n,s>="a"&&s<="z",s>="A"&&s<="Z",s>="0"&&s<="9",this.allowedNonAlphanumericCharacters.includes(s))}updatePasswordCharacterOptionsStatuses(e,n,s,i,r){this.customStrengthOptions.containsLowercaseLetter&&(e.containsLowercaseLetter||(e.containsLowercaseLetter=n)),this.customStrengthOptions.containsUppercaseLetter&&(e.containsUppercaseLetter||(e.containsUppercaseLetter=s)),this.customStrengthOptions.containsNumericCharacter&&(e.containsNumericCharacter||(e.containsNumericCharacter=i)),this.customStrengthOptions.containsNonAlphanumericCharacter&&(e.containsNonAlphanumericCharacter||(e.containsNonAlphanumericCharacter=r))}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class EN{constructor(e,n,s,i){this.app=e,this.heartbeatServiceProvider=n,this.appCheckServiceProvider=s,this.config=i,this.currentUser=null,this.emulatorConfig=null,this.operations=Promise.resolve(),this.authStateSubscription=new Im(this),this.idTokenSubscription=new Im(this),this.beforeStateQueue=new yN(this),this.redirectUser=null,this.isProactiveRefreshEnabled=!1,this.EXPECTED_PASSWORD_POLICY_SCHEMA_VERSION=1,this._canInitEmulator=!0,this._isInitialized=!1,this._deleted=!1,this._initializationPromise=null,this._popupRedirectResolver=null,this._errorFactory=bb,this._agentRecaptchaConfig=null,this._tenantRecaptchaConfigs={},this._projectPasswordPolicy=null,this._tenantPasswordPolicies={},this.lastNotifiedUid=void 0,this.languageCode=null,this.tenantId=null,this.settings={appVerificationDisabledForTesting:!1},this.frameworks=[],this.name=e.name,this.clientVersion=i.sdkClientVersion}_initializeWithPersistence(e,n){return n&&(this._popupRedirectResolver=Jn(n)),this._initializationPromise=this.queue(async()=>{var s,i;if(!this._deleted&&(this.persistenceManager=await tr.create(this,e),!this._deleted)){if(!((s=this._popupRedirectResolver)===null||s===void 0)&&s._shouldInitProactively)try{await this._popupRedirectResolver._initialize(this)}catch{}await this.initializeCurrentUser(n),this.lastNotifiedUid=((i=this.currentUser)===null||i===void 0?void 0:i.uid)||null,!this._deleted&&(this._isInitialized=!0)}}),this._initializationPromise}async _onStorageEvent(){if(this._deleted)return;const e=await this.assertedPersistence.getCurrentUser();if(!(!this.currentUser&&!e)){if(this.currentUser&&e&&this.currentUser.uid===e.uid){this._currentUser._assign(e),await this.currentUser.getIdToken();return}await this._updateCurrentUser(e,!0)}}async initializeCurrentUserFromIdToken(e){try{const n=await Sb(this,{idToken:e}),s=await Xn._fromGetAccountInfoResponse(this,n,e);await this.directlySetCurrentUser(s)}catch(n){console.warn("FirebaseServerApp could not login user with provided authIdToken: ",n),await this.directlySetCurrentUser(null)}}async initializeCurrentUser(e){var n;if(Rs(this.app)){const o=this.app.settings.authIdToken;return o?new Promise(l=>{setTimeout(()=>this.initializeCurrentUserFromIdToken(o).then(l,l))}):this.directlySetCurrentUser(null)}const s=await this.assertedPersistence.getCurrentUser();let i=s,r=!1;if(e&&this.config.authDomain){await this.getOrInitRedirectPersistenceManager();const o=(n=this.redirectUser)===null||n===void 0?void 0:n._redirectEventId,l=i==null?void 0:i._redirectEventId,c=await this.tryRedirectSignIn(e);(!o||o===l)&&(c!=null&&c.user)&&(i=c.user,r=!0)}if(!i)return this.directlySetCurrentUser(null);if(!i._redirectEventId){if(r)try{await this.beforeStateQueue.runMiddleware(i)}catch(o){i=s,this._popupRedirectResolver._overrideRedirectResult(this,()=>Promise.reject(o))}return i?this.reloadAndSetCurrentUserOrClear(i):this.directlySetCurrentUser(null)}return ue(this._popupRedirectResolver,this,"argument-error"),await this.getOrInitRedirectPersistenceManager(),this.redirectUser&&this.redirectUser._redirectEventId===i._redirectEventId?this.directlySetCurrentUser(i):this.reloadAndSetCurrentUserOrClear(i)}async tryRedirectSignIn(e){let n=null;try{n=await this._popupRedirectResolver._completeRedirectFn(this,e,!0)}catch{await this._setRedirectUser(null)}return n}async reloadAndSetCurrentUserOrClear(e){try{await El(e)}catch(n){if((n==null?void 0:n.code)!=="auth/network-request-failed")return this.directlySetCurrentUser(null)}return this.directlySetCurrentUser(e)}useDeviceLanguage(){this.languageCode=eN()}async _delete(){this._deleted=!0}async updateCurrentUser(e){if(Rs(this.app))return Promise.reject(ui(this));const n=e?Un(e):null;return n&&ue(n.auth.config.apiKey===this.config.apiKey,this,"invalid-user-token"),this._updateCurrentUser(n&&n._clone(this))}async _updateCurrentUser(e,n=!1){if(!this._deleted)return e&&ue(this.tenantId===e.tenantId,this,"tenant-id-mismatch"),n||await this.beforeStateQueue.runMiddleware(e),this.queue(async()=>{await this.directlySetCurrentUser(e),this.notifyAuthListeners()})}async signOut(){return Rs(this.app)?Promise.reject(ui(this)):(await this.beforeStateQueue.runMiddleware(null),(this.redirectPersistenceManager||this._popupRedirectResolver)&&await this._setRedirectUser(null),this._updateCurrentUser(null,!0))}setPersistence(e){return Rs(this.app)?Promise.reject(ui(this)):this.queue(async()=>{await this.assertedPersistence.setPersistence(Jn(e))})}_getRecaptchaConfig(){return this.tenantId==null?this._agentRecaptchaConfig:this._tenantRecaptchaConfigs[this.tenantId]}async validatePassword(e){this._getPasswordPolicyInternal()||await this._updatePasswordPolicy();const n=this._getPasswordPolicyInternal();return n.schemaVersion!==this.EXPECTED_PASSWORD_POLICY_SCHEMA_VERSION?Promise.reject(this._errorFactory.create("unsupported-password-policy-schema-version",{})):n.validatePassword(e)}_getPasswordPolicyInternal(){return this.tenantId===null?this._projectPasswordPolicy:this._tenantPasswordPolicies[this.tenantId]}async _updatePasswordPolicy(){const e=await wN(this),n=new bN(e);this.tenantId===null?this._projectPasswordPolicy=n:this._tenantPasswordPolicies[this.tenantId]=n}_getPersistence(){return this.assertedPersistence.persistence.type}_updateErrorMap(e){this._errorFactory=new Bs("auth","Firebase",e())}onAuthStateChanged(e,n,s){return this.registerStateListener(this.authStateSubscription,e,n,s)}beforeAuthStateChanged(e,n){return this.beforeStateQueue.pushCallback(e,n)}onIdTokenChanged(e,n,s){return this.registerStateListener(this.idTokenSubscription,e,n,s)}authStateReady(){return new Promise((e,n)=>{if(this.currentUser)e();else{const s=this.onAuthStateChanged(()=>{s(),e()},n)}})}async revokeAccessToken(e){if(this.currentUser){const n=await this.currentUser.getIdToken(),s={providerId:"apple.com",tokenType:"ACCESS_TOKEN",token:e,idToken:n};this.tenantId!=null&&(s.tenantId=this.tenantId),await gN(this,s)}}toJSON(){var e;return{apiKey:this.config.apiKey,authDomain:this.config.authDomain,appName:this.name,currentUser:(e=this._currentUser)===null||e===void 0?void 0:e.toJSON()}}async _setRedirectUser(e,n){const s=await this.getOrInitRedirectPersistenceManager(n);return e===null?s.removeCurrentUser():s.setCurrentUser(e)}async getOrInitRedirectPersistenceManager(e){if(!this.redirectPersistenceManager){const n=e&&Jn(e)||this._popupRedirectResolver;ue(n,this,"argument-error"),this.redirectPersistenceManager=await tr.create(this,[Jn(n._redirectPersistence)],"redirectUser"),this.redirectUser=await this.redirectPersistenceManager.getCurrentUser()}return this.redirectPersistenceManager}async _redirectUserForId(e){var n,s;return this._isInitialized&&await this.queue(async()=>{}),((n=this._currentUser)===null||n===void 0?void 0:n._redirectEventId)===e?this._currentUser:((s=this.redirectUser)===null||s===void 0?void 0:s._redirectEventId)===e?this.redirectUser:null}async _persistUserIfCurrent(e){if(e===this.currentUser)return this.queue(async()=>this.directlySetCurrentUser(e))}_notifyListenersIfCurrent(e){e===this.currentUser&&this.notifyAuthListeners()}_key(){return`${this.config.authDomain}:${this.config.apiKey}:${this.name}`}_startProactiveRefresh(){this.isProactiveRefreshEnabled=!0,this.currentUser&&this._currentUser._startProactiveRefresh()}_stopProactiveRefresh(){this.isProactiveRefreshEnabled=!1,this.currentUser&&this._currentUser._stopProactiveRefresh()}get _currentUser(){return this.currentUser}notifyAuthListeners(){var e,n;if(!this._isInitialized)return;this.idTokenSubscription.next(this.currentUser);const s=(n=(e=this.currentUser)===null||e===void 0?void 0:e.uid)!==null&&n!==void 0?n:null;this.lastNotifiedUid!==s&&(this.lastNotifiedUid=s,this.authStateSubscription.next(this.currentUser))}registerStateListener(e,n,s,i){if(this._deleted)return()=>{};const r=typeof n=="function"?n:n.next.bind(n);let o=!1;const l=this._isInitialized?Promise.resolve():this._initializationPromise;if(ue(l,this,"internal-error"),l.then(()=>{o||r(this.currentUser)}),typeof n=="function"){const c=e.addObserver(n,s,i);return()=>{o=!0,c()}}else{const c=e.addObserver(n);return()=>{o=!0,c()}}}async directlySetCurrentUser(e){this.currentUser&&this.currentUser!==e&&this._currentUser._stopProactiveRefresh(),e&&this.isProactiveRefreshEnabled&&e._startProactiveRefresh(),this.currentUser=e,e?await this.assertedPersistence.setCurrentUser(e):await this.assertedPersistence.removeCurrentUser()}queue(e){return this.operations=this.operations.then(e,e),this.operations}get assertedPersistence(){return ue(this.persistenceManager,this,"internal-error"),this.persistenceManager}_logFramework(e){!e||this.frameworks.includes(e)||(this.frameworks.push(e),this.frameworks.sort(),this.clientVersion=Fb(this.config.clientPlatform,this._getFrameworks()))}_getFrameworks(){return this.frameworks}async _getAdditionalHeaders(){var e;const n={"X-Client-Version":this.clientVersion};this.app.options.appId&&(n["X-Firebase-gmpid"]=this.app.options.appId);const s=await((e=this.heartbeatServiceProvider.getImmediate({optional:!0}))===null||e===void 0?void 0:e.getHeartbeatsHeader());s&&(n["X-Firebase-Client"]=s);const i=await this._getAppCheckToken();return i&&(n["X-Firebase-AppCheck"]=i),n}async _getAppCheckToken(){var e;const n=await((e=this.appCheckServiceProvider.getImmediate({optional:!0}))===null||e===void 0?void 0:e.getToken());return n!=null&&n.error&&JO(`Error while retrieving App Check token: ${n.error}`),n==null?void 0:n.token}}function jo(t){return Un(t)}class Im{constructor(e){this.auth=e,this.observer=null,this.addObserver=fk(n=>this.observer=n)}get next(){return ue(this.observer,this.auth,"internal-error"),this.observer.next.bind(this.observer)}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let fc={async loadJS(){throw new Error("Unable to load external scripts")},recaptchaV2Script:"",recaptchaEnterpriseScript:"",gapiScript:""};function TN(t){fc=t}function Ub(t){return fc.loadJS(t)}function CN(){return fc.recaptchaEnterpriseScript}function IN(){return fc.gapiScript}function SN(t){return`__${t}${Math.floor(Math.random()*1e6)}`}const AN="recaptcha-enterprise",kN="NO_RECAPTCHA";class RN{constructor(e){this.type=AN,this.auth=jo(e)}async verify(e="verify",n=!1){async function s(r){if(!n){if(r.tenantId==null&&r._agentRecaptchaConfig!=null)return r._agentRecaptchaConfig.siteKey;if(r.tenantId!=null&&r._tenantRecaptchaConfigs[r.tenantId]!==void 0)return r._tenantRecaptchaConfigs[r.tenantId].siteKey}return new Promise(async(o,l)=>{aN(r,{clientType:"CLIENT_TYPE_WEB",version:"RECAPTCHA_ENTERPRISE"}).then(c=>{if(c.recaptchaKey===void 0)l(new Error("recaptcha Enterprise site key undefined"));else{const u=new oN(c);return r.tenantId==null?r._agentRecaptchaConfig=u:r._tenantRecaptchaConfigs[r.tenantId]=u,o(u.siteKey)}}).catch(c=>{l(c)})})}function i(r,o,l){const c=window.grecaptcha;vm(c)?c.enterprise.ready(()=>{c.enterprise.execute(r,{action:e}).then(u=>{o(u)}).catch(()=>{o(kN)})}):l(Error("No reCAPTCHA enterprise script loaded."))}return new Promise((r,o)=>{s(this.auth).then(l=>{if(!n&&vm(window.grecaptcha))i(l,r,o);else{if(typeof window>"u"){o(new Error("RecaptchaVerifier is only supported in browser"));return}let c=CN();c.length!==0&&(c+=l),Ub(c).then(()=>{i(l,r,o)}).catch(u=>{o(u)})}}).catch(l=>{o(l)})})}}async function Sm(t,e,n,s=!1){const i=new RN(t);let r;try{r=await i.verify(n)}catch{r=await i.verify(n,!0)}const o=Object.assign({},e);return s?Object.assign(o,{captchaResp:r}):Object.assign(o,{captchaResponse:r}),Object.assign(o,{clientType:"CLIENT_TYPE_WEB"}),Object.assign(o,{recaptchaVersion:"RECAPTCHA_ENTERPRISE"}),o}async function PN(t,e,n,s){var i;if(!((i=t._getRecaptchaConfig())===null||i===void 0)&&i.isProviderEnabled("EMAIL_PASSWORD_PROVIDER")){const r=await Sm(t,e,n,n==="getOobCode");return s(t,r)}else return s(t,e).catch(async r=>{if(r.code==="auth/missing-recaptcha-token"){console.log(`${n} is protected by reCAPTCHA Enterprise for this project. Automatically triggering the reCAPTCHA flow and restarting the flow.`);const o=await Sm(t,e,n,n==="getOobCode");return s(t,o)}else return Promise.reject(r)})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function $b(t,e){const n=$o(t,"auth");if(n.isInitialized()){const i=n.getImmediate(),r=n.getOptions();if(dl(r,e??{}))return i;rs(i,"already-initialized")}return n.initialize({options:e})}function ON(t,e){const n=(e==null?void 0:e.persistence)||[],s=(Array.isArray(n)?n:[n]).map(Jn);e!=null&&e.errorMap&&t._updateErrorMap(e.errorMap),t._initializeWithPersistence(s,e==null?void 0:e.popupRedirectResolver)}function NN(t,e,n){const s=jo(t);ue(s._canInitEmulator,s,"emulator-config-failed"),ue(/^https?:\/\//.test(e),s,"invalid-emulator-scheme");const i=!1,r=Hb(e),{host:o,port:l}=xN(e),c=l===null?"":`:${l}`;s.config.emulator={url:`${r}//${o}${c}/`},s.settings.appVerificationDisabledForTesting=!0,s.emulatorConfig=Object.freeze({host:o,port:l,protocol:r.replace(":",""),options:Object.freeze({disableWarnings:i})}),DN()}function Hb(t){const e=t.indexOf(":");return e<0?"":t.substr(0,e+1)}function xN(t){const e=Hb(t),n=/(\/\/)?([^?#/]+)/.exec(t.substr(e.length));if(!n)return{host:"",port:null};const s=n[2].split("@").pop()||"",i=/^(\[[^\]]+\])(:|$)/.exec(s);if(i){const r=i[1];return{host:r,port:Am(s.substr(r.length+1))}}else{const[r,o]=s.split(":");return{host:r,port:Am(o)}}}function Am(t){if(!t)return null;const e=Number(t);return isNaN(e)?null:e}function DN(){function t(){const e=document.createElement("p"),n=e.style;e.innerText="Running in emulator mode. Do not use with production credentials.",n.position="fixed",n.width="100%",n.backgroundColor="#ffffff",n.border=".1em solid #000000",n.color="#b50000",n.bottom="0px",n.left="0px",n.margin="0px",n.zIndex="10000",n.textAlign="center",e.classList.add("firebase-emulator-warning"),document.body.appendChild(e)}typeof console<"u"&&typeof console.info=="function"&&console.info("WARNING: You are using the Auth Emulator, which is intended for local testing only.  Do not use with production credentials."),typeof window<"u"&&typeof document<"u"&&(document.readyState==="loading"?window.addEventListener("DOMContentLoaded",t):t())}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Bb{constructor(e,n){this.providerId=e,this.signInMethod=n}toJSON(){return Yn("not implemented")}_getIdTokenResponse(e){return Yn("not implemented")}_linkToIdToken(e,n){return Yn("not implemented")}_getReauthenticationResolver(e){return Yn("not implemented")}}async function LN(t,e){return Vs(t,"POST","/v1/accounts:sendOobCode",Bo(t,e))}async function MN(t,e){return LN(t,e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function nr(t,e){return sN(t,"POST","/v1/accounts:signInWithIdp",Bo(t,e))}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const FN="http://localhost";class pi extends Bb{constructor(){super(...arguments),this.pendingToken=null}static _fromParams(e){const n=new pi(e.providerId,e.signInMethod);return e.idToken||e.accessToken?(e.idToken&&(n.idToken=e.idToken),e.accessToken&&(n.accessToken=e.accessToken),e.nonce&&!e.pendingToken&&(n.nonce=e.nonce),e.pendingToken&&(n.pendingToken=e.pendingToken)):e.oauthToken&&e.oauthTokenSecret?(n.accessToken=e.oauthToken,n.secret=e.oauthTokenSecret):rs("argument-error"),n}toJSON(){return{idToken:this.idToken,accessToken:this.accessToken,secret:this.secret,nonce:this.nonce,pendingToken:this.pendingToken,providerId:this.providerId,signInMethod:this.signInMethod}}static fromJSON(e){const n=typeof e=="string"?JSON.parse(e):e,{providerId:s,signInMethod:i}=n,r=qd(n,["providerId","signInMethod"]);if(!s||!i)return null;const o=new pi(s,i);return o.idToken=r.idToken||void 0,o.accessToken=r.accessToken||void 0,o.secret=r.secret,o.nonce=r.nonce,o.pendingToken=r.pendingToken||null,o}_getIdTokenResponse(e){const n=this.buildRequest();return nr(e,n)}_linkToIdToken(e,n){const s=this.buildRequest();return s.idToken=n,nr(e,s)}_getReauthenticationResolver(e){const n=this.buildRequest();return n.autoCreate=!1,nr(e,n)}buildRequest(){const e={requestUri:FN,returnSecureToken:!0};if(this.pendingToken)e.pendingToken=this.pendingToken;else{const n={};this.idToken&&(n.id_token=this.idToken),this.accessToken&&(n.access_token=this.accessToken),this.secret&&(n.oauth_token_secret=this.secret),n.providerId=this.providerId,this.nonce&&!this.pendingToken&&(n.nonce=this.nonce),e.postBody=vr(n)}return e}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class jb{constructor(e){this.providerId=e,this.defaultLanguageCode=null,this.customParameters={}}setDefaultLanguage(e){this.defaultLanguageCode=e}setCustomParameters(e){return this.customParameters=e,this}getCustomParameters(){return this.customParameters}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Vo extends jb{constructor(){super(...arguments),this.scopes=[]}addScope(e){return this.scopes.includes(e)||this.scopes.push(e),this}getScopes(){return[...this.scopes]}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Is extends Vo{constructor(){super("facebook.com")}static credential(e){return pi._fromParams({providerId:Is.PROVIDER_ID,signInMethod:Is.FACEBOOK_SIGN_IN_METHOD,accessToken:e})}static credentialFromResult(e){return Is.credentialFromTaggedObject(e)}static credentialFromError(e){return Is.credentialFromTaggedObject(e.customData||{})}static credentialFromTaggedObject({_tokenResponse:e}){if(!e||!("oauthAccessToken"in e)||!e.oauthAccessToken)return null;try{return Is.credential(e.oauthAccessToken)}catch{return null}}}Is.FACEBOOK_SIGN_IN_METHOD="facebook.com";Is.PROVIDER_ID="facebook.com";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ss extends Vo{constructor(){super("google.com"),this.addScope("profile")}static credential(e,n){return pi._fromParams({providerId:Ss.PROVIDER_ID,signInMethod:Ss.GOOGLE_SIGN_IN_METHOD,idToken:e,accessToken:n})}static credentialFromResult(e){return Ss.credentialFromTaggedObject(e)}static credentialFromError(e){return Ss.credentialFromTaggedObject(e.customData||{})}static credentialFromTaggedObject({_tokenResponse:e}){if(!e)return null;const{oauthIdToken:n,oauthAccessToken:s}=e;if(!n&&!s)return null;try{return Ss.credential(n,s)}catch{return null}}}Ss.GOOGLE_SIGN_IN_METHOD="google.com";Ss.PROVIDER_ID="google.com";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class As extends Vo{constructor(){super("github.com")}static credential(e){return pi._fromParams({providerId:As.PROVIDER_ID,signInMethod:As.GITHUB_SIGN_IN_METHOD,accessToken:e})}static credentialFromResult(e){return As.credentialFromTaggedObject(e)}static credentialFromError(e){return As.credentialFromTaggedObject(e.customData||{})}static credentialFromTaggedObject({_tokenResponse:e}){if(!e||!("oauthAccessToken"in e)||!e.oauthAccessToken)return null;try{return As.credential(e.oauthAccessToken)}catch{return null}}}As.GITHUB_SIGN_IN_METHOD="github.com";As.PROVIDER_ID="github.com";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class ks extends Vo{constructor(){super("twitter.com")}static credential(e,n){return pi._fromParams({providerId:ks.PROVIDER_ID,signInMethod:ks.TWITTER_SIGN_IN_METHOD,oauthToken:e,oauthTokenSecret:n})}static credentialFromResult(e){return ks.credentialFromTaggedObject(e)}static credentialFromError(e){return ks.credentialFromTaggedObject(e.customData||{})}static credentialFromTaggedObject({_tokenResponse:e}){if(!e)return null;const{oauthAccessToken:n,oauthTokenSecret:s}=e;if(!n||!s)return null;try{return ks.credential(n,s)}catch{return null}}}ks.TWITTER_SIGN_IN_METHOD="twitter.com";ks.PROVIDER_ID="twitter.com";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class fr{constructor(e){this.user=e.user,this.providerId=e.providerId,this._tokenResponse=e._tokenResponse,this.operationType=e.operationType}static async _fromIdTokenResponse(e,n,s,i=!1){const r=await Xn._fromIdTokenResponse(e,s,i),o=km(s);return new fr({user:r,providerId:o,_tokenResponse:s,operationType:n})}static async _forOperation(e,n,s){await e._updateTokensIfNecessary(s,!0);const i=km(s);return new fr({user:e,providerId:i,_tokenResponse:s,operationType:n})}}function km(t){return t.providerId?t.providerId:"phoneNumber"in t?"phone":null}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Tl extends Sn{constructor(e,n,s,i){var r;super(n.code,n.message),this.operationType=s,this.user=i,Object.setPrototypeOf(this,Tl.prototype),this.customData={appName:e.name,tenantId:(r=e.tenantId)!==null&&r!==void 0?r:void 0,_serverResponse:n.customData._serverResponse,operationType:s}}static _fromErrorAndOperation(e,n,s,i){return new Tl(e,n,s,i)}}function Vb(t,e,n,s){return(e==="reauthenticate"?n._getReauthenticationResolver(t):n._getIdTokenResponse(t)).catch(r=>{throw r.code==="auth/multi-factor-auth-required"?Tl._fromErrorAndOperation(t,r,e,s):r})}async function UN(t,e,n=!1){const s=await Ao(t,e._linkToIdToken(t.auth,await t.getIdToken()),n);return fr._forOperation(t,"link",s)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function $N(t,e,n=!1){const{auth:s}=t;if(Rs(s.app))return Promise.reject(ui(s));const i="reauthenticate";try{const r=await Ao(t,Vb(s,i,e,t),n);ue(r.idToken,s,"internal-error");const o=Yd(r.idToken);ue(o,s,"internal-error");const{sub:l}=o;return ue(t.uid===l,s,"user-mismatch"),fr._forOperation(t,i,r)}catch(r){throw(r==null?void 0:r.code)==="auth/user-not-found"&&rs(s,"user-mismatch"),r}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function HN(t,e,n=!1){if(Rs(t.app))return Promise.reject(ui(t));const s="signIn",i=await Vb(t,s,e),r=await fr._fromIdTokenResponse(t,s,i);return n||await t._updateCurrentUser(r.user),r}async function jH(t,e,n){const s=jo(t);await PN(s,{requestType:"PASSWORD_RESET",email:e,clientType:"CLIENT_TYPE_WEB"},"getOobCode",MN)}function Wb(t,e,n,s){return Un(t).onIdTokenChanged(e,n,s)}function BN(t,e,n){return Un(t).beforeAuthStateChanged(e,n)}const Cl="__sak";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Kb{constructor(e,n){this.storageRetriever=e,this.type=n}_isAvailable(){try{return this.storage?(this.storage.setItem(Cl,"1"),this.storage.removeItem(Cl),Promise.resolve(!0)):Promise.resolve(!1)}catch{return Promise.resolve(!1)}}_set(e,n){return this.storage.setItem(e,JSON.stringify(n)),Promise.resolve()}_get(e){const n=this.storage.getItem(e);return Promise.resolve(n?JSON.parse(n):null)}_remove(e){return this.storage.removeItem(e),Promise.resolve()}get storage(){return this.storageRetriever()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const jN=1e3,VN=10;class qb extends Kb{constructor(){super(()=>window.localStorage,"LOCAL"),this.boundEventHandler=(e,n)=>this.onStorageEvent(e,n),this.listeners={},this.localCache={},this.pollTimer=null,this.fallbackToPolling=Mb(),this._shouldAllowMigration=!0}forAllChangedKeys(e){for(const n of Object.keys(this.listeners)){const s=this.storage.getItem(n),i=this.localCache[n];s!==i&&e(n,i,s)}}onStorageEvent(e,n=!1){if(!e.key){this.forAllChangedKeys((o,l,c)=>{this.notifyListeners(o,c)});return}const s=e.key;n?this.detachListener():this.stopPolling();const i=()=>{const o=this.storage.getItem(s);!n&&this.localCache[s]===o||this.notifyListeners(s,o)},r=this.storage.getItem(s);_N()&&r!==e.newValue&&e.newValue!==e.oldValue?setTimeout(i,VN):i()}notifyListeners(e,n){this.localCache[e]=n;const s=this.listeners[e];if(s)for(const i of Array.from(s))i(n&&JSON.parse(n))}startPolling(){this.stopPolling(),this.pollTimer=setInterval(()=>{this.forAllChangedKeys((e,n,s)=>{this.onStorageEvent(new StorageEvent("storage",{key:e,oldValue:n,newValue:s}),!0)})},jN)}stopPolling(){this.pollTimer&&(clearInterval(this.pollTimer),this.pollTimer=null)}attachListener(){window.addEventListener("storage",this.boundEventHandler)}detachListener(){window.removeEventListener("storage",this.boundEventHandler)}_addListener(e,n){Object.keys(this.listeners).length===0&&(this.fallbackToPolling?this.startPolling():this.attachListener()),this.listeners[e]||(this.listeners[e]=new Set,this.localCache[e]=this.storage.getItem(e)),this.listeners[e].add(n)}_removeListener(e,n){this.listeners[e]&&(this.listeners[e].delete(n),this.listeners[e].size===0&&delete this.listeners[e]),Object.keys(this.listeners).length===0&&(this.detachListener(),this.stopPolling())}async _set(e,n){await super._set(e,n),this.localCache[e]=JSON.stringify(n)}async _get(e){const n=await super._get(e);return this.localCache[e]=JSON.stringify(n),n}async _remove(e){await super._remove(e),delete this.localCache[e]}}qb.type="LOCAL";const zb=qb;/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Gb extends Kb{constructor(){super(()=>window.sessionStorage,"SESSION")}_addListener(e,n){}_removeListener(e,n){}}Gb.type="SESSION";const Yb=Gb;/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function WN(t){return Promise.all(t.map(async e=>{try{return{fulfilled:!0,value:await e}}catch(n){return{fulfilled:!1,reason:n}}}))}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class pc{constructor(e){this.eventTarget=e,this.handlersMap={},this.boundEventHandler=this.handleEvent.bind(this)}static _getInstance(e){const n=this.receivers.find(i=>i.isListeningto(e));if(n)return n;const s=new pc(e);return this.receivers.push(s),s}isListeningto(e){return this.eventTarget===e}async handleEvent(e){const n=e,{eventId:s,eventType:i,data:r}=n.data,o=this.handlersMap[i];if(!(o!=null&&o.size))return;n.ports[0].postMessage({status:"ack",eventId:s,eventType:i});const l=Array.from(o).map(async u=>u(n.origin,r)),c=await WN(l);n.ports[0].postMessage({status:"done",eventId:s,eventType:i,response:c})}_subscribe(e,n){Object.keys(this.handlersMap).length===0&&this.eventTarget.addEventListener("message",this.boundEventHandler),this.handlersMap[e]||(this.handlersMap[e]=new Set),this.handlersMap[e].add(n)}_unsubscribe(e,n){this.handlersMap[e]&&n&&this.handlersMap[e].delete(n),(!n||this.handlersMap[e].size===0)&&delete this.handlersMap[e],Object.keys(this.handlersMap).length===0&&this.eventTarget.removeEventListener("message",this.boundEventHandler)}}pc.receivers=[];/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Jd(t="",e=10){let n="";for(let s=0;s<e;s++)n+=Math.floor(Math.random()*10);return t+n}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class KN{constructor(e){this.target=e,this.handlers=new Set}removeMessageHandler(e){e.messageChannel&&(e.messageChannel.port1.removeEventListener("message",e.onMessage),e.messageChannel.port1.close()),this.handlers.delete(e)}async _send(e,n,s=50){const i=typeof MessageChannel<"u"?new MessageChannel:null;if(!i)throw new Error("connection_unavailable");let r,o;return new Promise((l,c)=>{const u=Jd("",20);i.port1.start();const h=setTimeout(()=>{c(new Error("unsupported_event"))},s);o={messageChannel:i,onMessage(f){const g=f;if(g.data.eventId===u)switch(g.data.status){case"ack":clearTimeout(h),r=setTimeout(()=>{c(new Error("timeout"))},3e3);break;case"done":clearTimeout(r),l(g.data.response);break;default:clearTimeout(h),clearTimeout(r),c(new Error("invalid_response"));break}}},this.handlers.add(o),i.port1.addEventListener("message",o.onMessage),this.target.postMessage({eventType:e,eventId:u,data:n},[i.port2])}).finally(()=>{o&&this.removeMessageHandler(o)})}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Dn(){return window}function qN(t){Dn().location.href=t}/**
 * @license
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Xb(){return typeof Dn().WorkerGlobalScope<"u"&&typeof Dn().importScripts=="function"}async function zN(){if(!(navigator!=null&&navigator.serviceWorker))return null;try{return(await navigator.serviceWorker.ready).active}catch{return null}}function GN(){var t;return((t=navigator==null?void 0:navigator.serviceWorker)===null||t===void 0?void 0:t.controller)||null}function YN(){return Xb()?self:null}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Jb="firebaseLocalStorageDb",XN=1,Il="firebaseLocalStorage",Qb="fbase_key";class Wo{constructor(e){this.request=e}toPromise(){return new Promise((e,n)=>{this.request.addEventListener("success",()=>{e(this.request.result)}),this.request.addEventListener("error",()=>{n(this.request.error)})})}}function gc(t,e){return t.transaction([Il],e?"readwrite":"readonly").objectStore(Il)}function JN(){const t=indexedDB.deleteDatabase(Jb);return new Wo(t).toPromise()}function Rh(){const t=indexedDB.open(Jb,XN);return new Promise((e,n)=>{t.addEventListener("error",()=>{n(t.error)}),t.addEventListener("upgradeneeded",()=>{const s=t.result;try{s.createObjectStore(Il,{keyPath:Qb})}catch(i){n(i)}}),t.addEventListener("success",async()=>{const s=t.result;s.objectStoreNames.contains(Il)?e(s):(s.close(),await JN(),e(await Rh()))})})}async function Rm(t,e,n){const s=gc(t,!0).put({[Qb]:e,value:n});return new Wo(s).toPromise()}async function QN(t,e){const n=gc(t,!1).get(e),s=await new Wo(n).toPromise();return s===void 0?null:s.value}function Pm(t,e){const n=gc(t,!0).delete(e);return new Wo(n).toPromise()}const ZN=800,ex=3;class Zb{constructor(){this.type="LOCAL",this._shouldAllowMigration=!0,this.listeners={},this.localCache={},this.pollTimer=null,this.pendingWrites=0,this.receiver=null,this.sender=null,this.serviceWorkerReceiverAvailable=!1,this.activeServiceWorker=null,this._workerInitializationPromise=this.initializeServiceWorkerMessaging().then(()=>{},()=>{})}async _openDb(){return this.db?this.db:(this.db=await Rh(),this.db)}async _withRetries(e){let n=0;for(;;)try{const s=await this._openDb();return await e(s)}catch(s){if(n++>ex)throw s;this.db&&(this.db.close(),this.db=void 0)}}async initializeServiceWorkerMessaging(){return Xb()?this.initializeReceiver():this.initializeSender()}async initializeReceiver(){this.receiver=pc._getInstance(YN()),this.receiver._subscribe("keyChanged",async(e,n)=>({keyProcessed:(await this._poll()).includes(n.key)})),this.receiver._subscribe("ping",async(e,n)=>["keyChanged"])}async initializeSender(){var e,n;if(this.activeServiceWorker=await zN(),!this.activeServiceWorker)return;this.sender=new KN(this.activeServiceWorker);const s=await this.sender._send("ping",{},800);s&&!((e=s[0])===null||e===void 0)&&e.fulfilled&&!((n=s[0])===null||n===void 0)&&n.value.includes("keyChanged")&&(this.serviceWorkerReceiverAvailable=!0)}async notifyServiceWorker(e){if(!(!this.sender||!this.activeServiceWorker||GN()!==this.activeServiceWorker))try{await this.sender._send("keyChanged",{key:e},this.serviceWorkerReceiverAvailable?800:50)}catch{}}async _isAvailable(){try{if(!indexedDB)return!1;const e=await Rh();return await Rm(e,Cl,"1"),await Pm(e,Cl),!0}catch{}return!1}async _withPendingWrite(e){this.pendingWrites++;try{await e()}finally{this.pendingWrites--}}async _set(e,n){return this._withPendingWrite(async()=>(await this._withRetries(s=>Rm(s,e,n)),this.localCache[e]=n,this.notifyServiceWorker(e)))}async _get(e){const n=await this._withRetries(s=>QN(s,e));return this.localCache[e]=n,n}async _remove(e){return this._withPendingWrite(async()=>(await this._withRetries(n=>Pm(n,e)),delete this.localCache[e],this.notifyServiceWorker(e)))}async _poll(){const e=await this._withRetries(i=>{const r=gc(i,!1).getAll();return new Wo(r).toPromise()});if(!e)return[];if(this.pendingWrites!==0)return[];const n=[],s=new Set;if(e.length!==0)for(const{fbase_key:i,value:r}of e)s.add(i),JSON.stringify(this.localCache[i])!==JSON.stringify(r)&&(this.notifyListeners(i,r),n.push(i));for(const i of Object.keys(this.localCache))this.localCache[i]&&!s.has(i)&&(this.notifyListeners(i,null),n.push(i));return n}notifyListeners(e,n){this.localCache[e]=n;const s=this.listeners[e];if(s)for(const i of Array.from(s))i(n)}startPolling(){this.stopPolling(),this.pollTimer=setInterval(async()=>this._poll(),ZN)}stopPolling(){this.pollTimer&&(clearInterval(this.pollTimer),this.pollTimer=null)}_addListener(e,n){Object.keys(this.listeners).length===0&&this.startPolling(),this.listeners[e]||(this.listeners[e]=new Set,this._get(e)),this.listeners[e].add(n)}_removeListener(e,n){this.listeners[e]&&(this.listeners[e].delete(n),this.listeners[e].size===0&&delete this.listeners[e]),Object.keys(this.listeners).length===0&&this.stopPolling()}}Zb.type="LOCAL";const e0=Zb;new Ho(3e4,6e4);/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function tx(t,e){return e?Jn(e):(ue(t._popupRedirectResolver,t,"argument-error"),t._popupRedirectResolver)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Qd extends Bb{constructor(e){super("custom","custom"),this.params=e}_getIdTokenResponse(e){return nr(e,this._buildIdpRequest())}_linkToIdToken(e,n){return nr(e,this._buildIdpRequest(n))}_getReauthenticationResolver(e){return nr(e,this._buildIdpRequest())}_buildIdpRequest(e){const n={requestUri:this.params.requestUri,sessionId:this.params.sessionId,postBody:this.params.postBody,tenantId:this.params.tenantId,pendingToken:this.params.pendingToken,returnSecureToken:!0,returnIdpCredential:!0};return e&&(n.idToken=e),n}}function nx(t){return HN(t.auth,new Qd(t),t.bypassAuthState)}function sx(t){const{auth:e,user:n}=t;return ue(n,e,"internal-error"),$N(n,new Qd(t),t.bypassAuthState)}async function ix(t){const{auth:e,user:n}=t;return ue(n,e,"internal-error"),UN(n,new Qd(t),t.bypassAuthState)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class t0{constructor(e,n,s,i,r=!1){this.auth=e,this.resolver=s,this.user=i,this.bypassAuthState=r,this.pendingPromise=null,this.eventManager=null,this.filter=Array.isArray(n)?n:[n]}execute(){return new Promise(async(e,n)=>{this.pendingPromise={resolve:e,reject:n};try{this.eventManager=await this.resolver._initialize(this.auth),await this.onExecution(),this.eventManager.registerConsumer(this)}catch(s){this.reject(s)}})}async onAuthEvent(e){const{urlResponse:n,sessionId:s,postBody:i,tenantId:r,error:o,type:l}=e;if(o){this.reject(o);return}const c={auth:this.auth,requestUri:n,sessionId:s,tenantId:r||void 0,postBody:i||void 0,user:this.user,bypassAuthState:this.bypassAuthState};try{this.resolve(await this.getIdpTask(l)(c))}catch(u){this.reject(u)}}onError(e){this.reject(e)}getIdpTask(e){switch(e){case"signInViaPopup":case"signInViaRedirect":return nx;case"linkViaPopup":case"linkViaRedirect":return ix;case"reauthViaPopup":case"reauthViaRedirect":return sx;default:rs(this.auth,"internal-error")}}resolve(e){os(this.pendingPromise,"Pending promise was never set"),this.pendingPromise.resolve(e),this.unregisterAndCleanUp()}reject(e){os(this.pendingPromise,"Pending promise was never set"),this.pendingPromise.reject(e),this.unregisterAndCleanUp()}unregisterAndCleanUp(){this.eventManager&&this.eventManager.unregisterConsumer(this),this.pendingPromise=null,this.cleanUp()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const rx=new Ho(2e3,1e4);class Vi extends t0{constructor(e,n,s,i,r){super(e,n,i,r),this.provider=s,this.authWindow=null,this.pollId=null,Vi.currentPopupAction&&Vi.currentPopupAction.cancel(),Vi.currentPopupAction=this}async executeNotNull(){const e=await this.execute();return ue(e,this.auth,"internal-error"),e}async onExecution(){os(this.filter.length===1,"Popup operations only handle one event");const e=Jd();this.authWindow=await this.resolver._openPopup(this.auth,this.provider,this.filter[0],e),this.authWindow.associatedEvent=e,this.resolver._originValidation(this.auth).catch(n=>{this.reject(n)}),this.resolver._isIframeWebStorageSupported(this.auth,n=>{n||this.reject(xn(this.auth,"web-storage-unsupported"))}),this.pollUserCancellation()}get eventId(){var e;return((e=this.authWindow)===null||e===void 0?void 0:e.associatedEvent)||null}cancel(){this.reject(xn(this.auth,"cancelled-popup-request"))}cleanUp(){this.authWindow&&this.authWindow.close(),this.pollId&&window.clearTimeout(this.pollId),this.authWindow=null,this.pollId=null,Vi.currentPopupAction=null}pollUserCancellation(){const e=()=>{var n,s;if(!((s=(n=this.authWindow)===null||n===void 0?void 0:n.window)===null||s===void 0)&&s.closed){this.pollId=window.setTimeout(()=>{this.pollId=null,this.reject(xn(this.auth,"popup-closed-by-user"))},8e3);return}this.pollId=window.setTimeout(e,rx.get())};e()}}Vi.currentPopupAction=null;/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ox="pendingRedirect",za=new Map;class ax extends t0{constructor(e,n,s=!1){super(e,["signInViaRedirect","linkViaRedirect","reauthViaRedirect","unknown"],n,void 0,s),this.eventId=null}async execute(){let e=za.get(this.auth._key());if(!e){try{const s=await lx(this.resolver,this.auth)?await super.execute():null;e=()=>Promise.resolve(s)}catch(n){e=()=>Promise.reject(n)}za.set(this.auth._key(),e)}return this.bypassAuthState||za.set(this.auth._key(),()=>Promise.resolve(null)),e()}async onAuthEvent(e){if(e.type==="signInViaRedirect")return super.onAuthEvent(e);if(e.type==="unknown"){this.resolve(null);return}if(e.eventId){const n=await this.auth._redirectUserForId(e.eventId);if(n)return this.user=n,super.onAuthEvent(e);this.resolve(null)}}async onExecution(){}cleanUp(){}}async function lx(t,e){const n=hx(e),s=ux(t);if(!await s._isAvailable())return!1;const i=await s._get(n)==="true";return await s._remove(n),i}function cx(t,e){za.set(t._key(),e)}function ux(t){return Jn(t._redirectPersistence)}function hx(t){return qa(ox,t.config.apiKey,t.name)}async function dx(t,e,n=!1){if(Rs(t.app))return Promise.reject(ui(t));const s=jo(t),i=tx(s,e),o=await new ax(s,i,n).execute();return o&&!n&&(delete o.user._redirectEventId,await s._persistUserIfCurrent(o.user),await s._setRedirectUser(null,e)),o}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const fx=10*60*1e3;class px{constructor(e){this.auth=e,this.cachedEventUids=new Set,this.consumers=new Set,this.queuedRedirectEvent=null,this.hasHandledPotentialRedirect=!1,this.lastProcessedEventTime=Date.now()}registerConsumer(e){this.consumers.add(e),this.queuedRedirectEvent&&this.isEventForConsumer(this.queuedRedirectEvent,e)&&(this.sendToConsumer(this.queuedRedirectEvent,e),this.saveEventToCache(this.queuedRedirectEvent),this.queuedRedirectEvent=null)}unregisterConsumer(e){this.consumers.delete(e)}onEvent(e){if(this.hasEventBeenHandled(e))return!1;let n=!1;return this.consumers.forEach(s=>{this.isEventForConsumer(e,s)&&(n=!0,this.sendToConsumer(e,s),this.saveEventToCache(e))}),this.hasHandledPotentialRedirect||!gx(e)||(this.hasHandledPotentialRedirect=!0,n||(this.queuedRedirectEvent=e,n=!0)),n}sendToConsumer(e,n){var s;if(e.error&&!n0(e)){const i=((s=e.error.code)===null||s===void 0?void 0:s.split("auth/")[1])||"internal-error";n.onError(xn(this.auth,i))}else n.onAuthEvent(e)}isEventForConsumer(e,n){const s=n.eventId===null||!!e.eventId&&e.eventId===n.eventId;return n.filter.includes(e.type)&&s}hasEventBeenHandled(e){return Date.now()-this.lastProcessedEventTime>=fx&&this.cachedEventUids.clear(),this.cachedEventUids.has(Om(e))}saveEventToCache(e){this.cachedEventUids.add(Om(e)),this.lastProcessedEventTime=Date.now()}}function Om(t){return[t.type,t.eventId,t.sessionId,t.tenantId].filter(e=>e).join("-")}function n0({type:t,error:e}){return t==="unknown"&&(e==null?void 0:e.code)==="auth/no-auth-event"}function gx(t){switch(t.type){case"signInViaRedirect":case"linkViaRedirect":case"reauthViaRedirect":return!0;case"unknown":return n0(t);default:return!1}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function mx(t,e={}){return Vs(t,"GET","/v1/projects",e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const _x=/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/,yx=/^https?/;async function wx(t){if(t.config.emulator)return;const{authorizedDomains:e}=await mx(t);for(const n of e)try{if(vx(n))return}catch{}rs(t,"unauthorized-domain")}function vx(t){const e=Ah(),{protocol:n,hostname:s}=new URL(e);if(t.startsWith("chrome-extension://")){const o=new URL(t);return o.hostname===""&&s===""?n==="chrome-extension:"&&t.replace("chrome-extension://","")===e.replace("chrome-extension://",""):n==="chrome-extension:"&&o.hostname===s}if(!yx.test(n))return!1;if(_x.test(t))return s===t;const i=t.replace(/\./g,"\\.");return new RegExp("^(.+\\."+i+"|"+i+")$","i").test(s)}/**
 * @license
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const bx=new Ho(3e4,6e4);function Nm(){const t=Dn().___jsl;if(t!=null&&t.H){for(const e of Object.keys(t.H))if(t.H[e].r=t.H[e].r||[],t.H[e].L=t.H[e].L||[],t.H[e].r=[...t.H[e].L],t.CP)for(let n=0;n<t.CP.length;n++)t.CP[n]=null}}function Ex(t){return new Promise((e,n)=>{var s,i,r;function o(){Nm(),gapi.load("gapi.iframes",{callback:()=>{e(gapi.iframes.getContext())},ontimeout:()=>{Nm(),n(xn(t,"network-request-failed"))},timeout:bx.get()})}if(!((i=(s=Dn().gapi)===null||s===void 0?void 0:s.iframes)===null||i===void 0)&&i.Iframe)e(gapi.iframes.getContext());else if(!((r=Dn().gapi)===null||r===void 0)&&r.load)o();else{const l=SN("iframefcb");return Dn()[l]=()=>{gapi.load?o():n(xn(t,"network-request-failed"))},Ub(`${IN()}?onload=${l}`).catch(c=>n(c))}}).catch(e=>{throw Ga=null,e})}let Ga=null;function Tx(t){return Ga=Ga||Ex(t),Ga}/**
 * @license
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Cx=new Ho(5e3,15e3),Ix="__/auth/iframe",Sx="emulator/auth/iframe",Ax={style:{position:"absolute",top:"-100px",width:"1px",height:"1px"},"aria-hidden":"true",tabindex:"-1"},kx=new Map([["identitytoolkit.googleapis.com","p"],["staging-identitytoolkit.sandbox.googleapis.com","s"],["test-identitytoolkit.sandbox.googleapis.com","t"]]);function Rx(t){const e=t.config;ue(e.authDomain,t,"auth-domain-config-required");const n=e.emulator?Gd(e,Sx):`https://${t.config.authDomain}/${Ix}`,s={apiKey:e.apiKey,appName:t.name,v:js},i=kx.get(t.config.apiHost);i&&(s.eid=i);const r=t._getFrameworks();return r.length&&(s.fw=r.join(",")),`${n}?${vr(s).slice(1)}`}async function Px(t){const e=await Tx(t),n=Dn().gapi;return ue(n,t,"internal-error"),e.open({where:document.body,url:Rx(t),messageHandlersFilter:n.iframes.CROSS_ORIGIN_IFRAMES_FILTER,attributes:Ax,dontclear:!0},s=>new Promise(async(i,r)=>{await s.restyle({setHideOnLeave:!1});const o=xn(t,"network-request-failed"),l=Dn().setTimeout(()=>{r(o)},Cx.get());function c(){Dn().clearTimeout(l),i(s)}s.ping(c).then(c,()=>{r(o)})}))}/**
 * @license
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ox={location:"yes",resizable:"yes",statusbar:"yes",toolbar:"no"},Nx=500,xx=600,Dx="_blank",Lx="http://localhost";class xm{constructor(e){this.window=e,this.associatedEvent=null}close(){if(this.window)try{this.window.close()}catch{}}}function Mx(t,e,n,s=Nx,i=xx){const r=Math.max((window.screen.availHeight-i)/2,0).toString(),o=Math.max((window.screen.availWidth-s)/2,0).toString();let l="";const c=Object.assign(Object.assign({},Ox),{width:s.toString(),height:i.toString(),top:r,left:o}),u=Lt().toLowerCase();n&&(l=Ob(u)?Dx:n),Rb(u)&&(e=e||Lx,c.scrollbars="yes");const h=Object.entries(c).reduce((g,[m,I])=>`${g}${m}=${I},`,"");if(mN(u)&&l!=="_self")return Fx(e||"",l),new xm(null);const f=window.open(e||"",l,h);ue(f,t,"popup-blocked");try{f.focus()}catch{}return new xm(f)}function Fx(t,e){const n=document.createElement("a");n.href=t,n.target=e;const s=document.createEvent("MouseEvent");s.initMouseEvent("click",!0,!0,window,1,0,0,0,0,!1,!1,!1,!1,1,null),n.dispatchEvent(s)}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ux="__/auth/handler",$x="emulator/auth/handler",Hx=encodeURIComponent("fac");async function Dm(t,e,n,s,i,r){ue(t.config.authDomain,t,"auth-domain-config-required"),ue(t.config.apiKey,t,"invalid-api-key");const o={apiKey:t.config.apiKey,appName:t.name,authType:n,redirectUrl:s,v:js,eventId:i};if(e instanceof jb){e.setDefaultLanguage(t.languageCode),o.providerId=e.providerId||"",lh(e.getCustomParameters())||(o.customParameters=JSON.stringify(e.getCustomParameters()));for(const[h,f]of Object.entries({}))o[h]=f}if(e instanceof Vo){const h=e.getScopes().filter(f=>f!=="");h.length>0&&(o.scopes=h.join(","))}t.tenantId&&(o.tid=t.tenantId);const l=o;for(const h of Object.keys(l))l[h]===void 0&&delete l[h];const c=await t._getAppCheckToken(),u=c?`#${Hx}=${encodeURIComponent(c)}`:"";return`${Bx(t)}?${vr(l).slice(1)}${u}`}function Bx({config:t}){return t.emulator?Gd(t,$x):`https://${t.authDomain}/${Ux}`}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Cu="webStorageSupport";class jx{constructor(){this.eventManagers={},this.iframes={},this.originValidationPromises={},this._redirectPersistence=Yb,this._completeRedirectFn=dx,this._overrideRedirectResult=cx}async _openPopup(e,n,s,i){var r;os((r=this.eventManagers[e._key()])===null||r===void 0?void 0:r.manager,"_initialize() not called before _openPopup()");const o=await Dm(e,n,s,Ah(),i);return Mx(e,o,Jd())}async _openRedirect(e,n,s,i){await this._originValidation(e);const r=await Dm(e,n,s,Ah(),i);return qN(r),new Promise(()=>{})}_initialize(e){const n=e._key();if(this.eventManagers[n]){const{manager:i,promise:r}=this.eventManagers[n];return i?Promise.resolve(i):(os(r,"If manager is not set, promise should be"),r)}const s=this.initAndGetManager(e);return this.eventManagers[n]={promise:s},s.catch(()=>{delete this.eventManagers[n]}),s}async initAndGetManager(e){const n=await Px(e),s=new px(e);return n.register("authEvent",i=>(ue(i==null?void 0:i.authEvent,e,"invalid-auth-event"),{status:s.onEvent(i.authEvent)?"ACK":"ERROR"}),gapi.iframes.CROSS_ORIGIN_IFRAMES_FILTER),this.eventManagers[e._key()]={manager:s},this.iframes[e._key()]=n,s}_isIframeWebStorageSupported(e,n){this.iframes[e._key()].send(Cu,{type:Cu},i=>{var r;const o=(r=i==null?void 0:i[0])===null||r===void 0?void 0:r[Cu];o!==void 0&&n(!!o),rs(e,"internal-error")},gapi.iframes.CROSS_ORIGIN_IFRAMES_FILTER)}_originValidation(e){const n=e._key();return this.originValidationPromises[n]||(this.originValidationPromises[n]=wx(e)),this.originValidationPromises[n]}get _shouldInitProactively(){return Mb()||Pb()||Xd()}}const s0=jx;var Lm="@firebase/auth",Mm="1.7.9";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Vx{constructor(e){this.auth=e,this.internalListeners=new Map}getUid(){var e;return this.assertAuthConfigured(),((e=this.auth.currentUser)===null||e===void 0?void 0:e.uid)||null}async getToken(e){return this.assertAuthConfigured(),await this.auth._initializationPromise,this.auth.currentUser?{accessToken:await this.auth.currentUser.getIdToken(e)}:null}addAuthTokenListener(e){if(this.assertAuthConfigured(),this.internalListeners.has(e))return;const n=this.auth.onIdTokenChanged(s=>{e((s==null?void 0:s.stsTokenManager.accessToken)||null)});this.internalListeners.set(e,n),this.updateProactiveRefresh()}removeAuthTokenListener(e){this.assertAuthConfigured();const n=this.internalListeners.get(e);n&&(this.internalListeners.delete(e),n(),this.updateProactiveRefresh())}assertAuthConfigured(){ue(this.auth._initializationPromise,"dependent-sdk-initialized-before-auth")}updateProactiveRefresh(){this.internalListeners.size>0?this.auth._startProactiveRefresh():this.auth._stopProactiveRefresh()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Wx(t){switch(t){case"Node":return"node";case"ReactNative":return"rn";case"Worker":return"webworker";case"Cordova":return"cordova";case"WebExtension":return"web-extension";default:return}}function Kx(t){Kt(new Mt("auth",(e,{options:n})=>{const s=e.getProvider("app").getImmediate(),i=e.getProvider("heartbeat"),r=e.getProvider("app-check-internal"),{apiKey:o,authDomain:l}=s.options;ue(o&&!o.includes(":"),"invalid-api-key",{appName:s.name});const c={apiKey:o,authDomain:l,clientPlatform:t,apiHost:"identitytoolkit.googleapis.com",tokenApiHost:"securetoken.googleapis.com",apiScheme:"https",sdkClientVersion:Fb(t)},u=new EN(s,i,r,c);return ON(u,n),u},"PUBLIC").setInstantiationMode("EXPLICIT").setInstanceCreatedCallback((e,n,s)=>{e.getProvider("auth-internal").initialize()})),Kt(new Mt("auth-internal",e=>{const n=jo(e.getProvider("auth").getImmediate());return(s=>new Vx(s))(n)},"PRIVATE").setInstantiationMode("EXPLICIT")),mt(Lm,Mm,Wx(t)),mt(Lm,Mm,"esm2017")}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const qx=5*60,zx=Jw("authIdTokenMaxAge")||qx;let Fm=null;const Gx=t=>async e=>{const n=e&&await e.getIdTokenResult(),s=n&&(new Date().getTime()-Date.parse(n.issuedAtTime))/1e3;if(s&&s>zx)return;const i=n==null?void 0:n.token;Fm!==i&&(Fm=i,await fetch(t,{method:i?"POST":"DELETE",headers:i?{Authorization:`Bearer ${i}`}:{}}))};function VH(t=Rd()){const e=$o(t,"auth");if(e.isInitialized())return e.getImmediate();const n=$b(t,{popupRedirectResolver:s0,persistence:[e0,zb,Yb]}),s=Jw("authTokenSyncURL");if(s&&typeof isSecureContext=="boolean"&&isSecureContext){const r=new URL(s,location.origin);if(location.origin===r.origin){const o=Gx(r.toString());BN(n,o,()=>o(n.currentUser)),Wb(n,l=>o(l))}}const i=nk("auth");return i&&NN(n,`http://${i}`),n}function Yx(){var t,e;return(e=(t=document.getElementsByTagName("head"))===null||t===void 0?void 0:t[0])!==null&&e!==void 0?e:document}TN({loadJS(t){return new Promise((e,n)=>{const s=document.createElement("script");s.setAttribute("src",t),s.onload=e,s.onerror=i=>{const r=xn("internal-error");r.customData=i,n(r)},s.type="text/javascript",s.charset="UTF-8",Yx().appendChild(s)})},gapiScript:"https://apis.google.com/js/api.js",recaptchaV2Script:"https://www.google.com/recaptcha/api.js",recaptchaEnterpriseScript:"https://www.google.com/recaptcha/enterprise.js?render="});Kx("Browser");/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Xx=new Map,Jx={activated:!1,tokenObservers:[]};function Cn(t){return Xx.get(t)||Object.assign({},Jx)}const Um={OFFSET_DURATION:5*60*1e3,RETRIAL_MIN_WAIT:30*1e3,RETRIAL_MAX_WAIT:16*60*1e3};/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Qx{constructor(e,n,s,i,r){if(this.operation=e,this.retryPolicy=n,this.getWaitDuration=s,this.lowerBound=i,this.upperBound=r,this.pending=null,this.nextErrorWaitInterval=i,i>r)throw new Error("Proactive refresh lower bound greater than upper bound!")}start(){this.nextErrorWaitInterval=this.lowerBound,this.process(!0).catch(()=>{})}stop(){this.pending&&(this.pending.reject("cancelled"),this.pending=null)}isRunning(){return!!this.pending}async process(e){this.stop();try{this.pending=new vo,this.pending.promise.catch(n=>{}),await Zx(this.getNextRun(e)),this.pending.resolve(),await this.pending.promise,this.pending=new vo,this.pending.promise.catch(n=>{}),await this.operation(),this.pending.resolve(),await this.pending.promise,this.process(!0).catch(()=>{})}catch(n){this.retryPolicy(n)?this.process(!1).catch(()=>{}):this.stop()}}getNextRun(e){if(e)return this.nextErrorWaitInterval=this.lowerBound,this.getWaitDuration();{const n=this.nextErrorWaitInterval;return this.nextErrorWaitInterval*=2,this.nextErrorWaitInterval>this.upperBound&&(this.nextErrorWaitInterval=this.upperBound),n}}}function Zx(t){return new Promise(e=>{setTimeout(e,t)})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const eD={"already-initialized":"You have already called initializeAppCheck() for FirebaseApp {$appName} with different options. To avoid this error, call initializeAppCheck() with the same options as when it was originally called. This will return the already initialized instance.","use-before-activation":"App Check is being used before initializeAppCheck() is called for FirebaseApp {$appName}. Call initializeAppCheck() before instantiating other Firebase services.","fetch-network-error":"Fetch failed to connect to a network. Check Internet connection. Original error: {$originalErrorMessage}.","fetch-parse-error":"Fetch client could not parse response. Original error: {$originalErrorMessage}.","fetch-status-error":"Fetch server returned an HTTP error status. HTTP status: {$httpStatus}.","storage-open":"Error thrown when opening storage. Original error: {$originalErrorMessage}.","storage-get":"Error thrown when reading from storage. Original error: {$originalErrorMessage}.","storage-set":"Error thrown when writing to storage. Original error: {$originalErrorMessage}.","recaptcha-error":"ReCAPTCHA error.",throttled:"Requests throttled due to {$httpStatus} error. Attempts allowed again after {$time}"},Sl=new Bs("appCheck","AppCheck",eD);function i0(t){if(!Cn(t).activated)throw Sl.create("use-before-activation",{appName:t.name})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const tD="firebase-app-check-database",nD=1,Ph="firebase-app-check-store";let Oa=null;function sD(){return Oa||(Oa=new Promise((t,e)=>{try{const n=indexedDB.open(tD,nD);n.onsuccess=s=>{t(s.target.result)},n.onerror=s=>{var i;e(Sl.create("storage-open",{originalErrorMessage:(i=s.target.error)===null||i===void 0?void 0:i.message}))},n.onupgradeneeded=s=>{const i=s.target.result;switch(s.oldVersion){case 0:i.createObjectStore(Ph,{keyPath:"compositeKey"})}}}catch(n){e(Sl.create("storage-open",{originalErrorMessage:n==null?void 0:n.message}))}}),Oa)}function iD(t,e){return rD(oD(t),e)}async function rD(t,e){const s=(await sD()).transaction(Ph,"readwrite"),r=s.objectStore(Ph).put({compositeKey:t,value:e});return new Promise((o,l)=>{r.onsuccess=c=>{o()},s.onerror=c=>{var u;l(Sl.create("storage-set",{originalErrorMessage:(u=c.target.error)===null||u===void 0?void 0:u.message}))}})}function oD(t){return`${t.options.appId}-${t.name}`}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Oh=new Uo("@firebase/app-check");function $m(t,e){return Sd()?iD(t,e).catch(n=>{Oh.warn(`Failed to write token to IndexedDB. Error: ${n}`)}):Promise.resolve()}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const aD={error:"UNKNOWN_ERROR"};function lD(t){return ic.encodeString(JSON.stringify(t),!1)}async function Nh(t,e=!1){const n=t.app;i0(n);const s=Cn(n);let i=s.token,r;if(i&&!Yr(i)&&(s.token=void 0,i=void 0),!i){const c=await s.cachedTokenPromise;c&&(Yr(c)?i=c:await $m(n,void 0))}if(!e&&i&&Yr(i))return{token:i.token};let o=!1;try{s.exchangeTokenPromise||(s.exchangeTokenPromise=s.provider.getToken().finally(()=>{s.exchangeTokenPromise=void 0}),o=!0),i=await Cn(n).exchangeTokenPromise}catch(c){c.code==="appCheck/throttled"?Oh.warn(c.message):Oh.error(c),r=c}let l;return i?r?Yr(i)?l={token:i.token,internalError:r}:l=Bm(r):(l={token:i.token},s.token=i,await $m(n,i)):l=Bm(r),o&&dD(n,l),l}async function cD(t){const e=t.app;i0(e);const{provider:n}=Cn(e);{const{token:s}=await n.getToken();return{token:s}}}function uD(t,e,n,s){const{app:i}=t,r=Cn(i),o={next:n,error:s,type:e};if(r.tokenObservers=[...r.tokenObservers,o],r.token&&Yr(r.token)){const l=r.token;Promise.resolve().then(()=>{n({token:l.token}),Hm(t)}).catch(()=>{})}r.cachedTokenPromise.then(()=>Hm(t))}function r0(t,e){const n=Cn(t),s=n.tokenObservers.filter(i=>i.next!==e);s.length===0&&n.tokenRefresher&&n.tokenRefresher.isRunning()&&n.tokenRefresher.stop(),n.tokenObservers=s}function Hm(t){const{app:e}=t,n=Cn(e);let s=n.tokenRefresher;s||(s=hD(t),n.tokenRefresher=s),!s.isRunning()&&n.isTokenAutoRefreshEnabled&&s.start()}function hD(t){const{app:e}=t;return new Qx(async()=>{const n=Cn(e);let s;if(n.token?s=await Nh(t,!0):s=await Nh(t),s.error)throw s.error;if(s.internalError)throw s.internalError},()=>!0,()=>{const n=Cn(e);if(n.token){let s=n.token.issuedAtTimeMillis+(n.token.expireTimeMillis-n.token.issuedAtTimeMillis)*.5+3e5;const i=n.token.expireTimeMillis-5*60*1e3;return s=Math.min(s,i),Math.max(0,s-Date.now())}else return 0},Um.RETRIAL_MIN_WAIT,Um.RETRIAL_MAX_WAIT)}function dD(t,e){const n=Cn(t).tokenObservers;for(const s of n)try{s.type==="EXTERNAL"&&e.error!=null?s.error(e.error):s.next(e)}catch{}}function Yr(t){return t.expireTimeMillis-Date.now()>0}function Bm(t){return{token:lD(aD),error:t}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class fD{constructor(e,n){this.app=e,this.heartbeatServiceProvider=n}_delete(){const{tokenObservers:e}=Cn(this.app);for(const n of e)r0(this.app,n.next);return Promise.resolve()}}function pD(t,e){return new fD(t,e)}function gD(t){return{getToken:e=>Nh(t,e),getLimitedUseToken:()=>cD(t),addTokenListener:e=>uD(t,"INTERNAL",e),removeTokenListener:e=>r0(t.app,e)}}const mD="@firebase/app-check",_D="0.8.8",yD="app-check",jm="app-check-internal";function wD(){Kt(new Mt(yD,t=>{const e=t.getProvider("app").getImmediate(),n=t.getProvider("heartbeat");return pD(e,n)},"PUBLIC").setInstantiationMode("EXPLICIT").setInstanceCreatedCallback((t,e,n)=>{t.getProvider(jm).initialize()})),Kt(new Mt(jm,t=>{const e=t.getProvider("app-check").getImmediate();return gD(e)},"PUBLIC").setInstantiationMode("EXPLICIT")),mt(mD,_D)}wD();const o0=Symbol("firebaseApp");function vD(t){return Ei()&&ht(o0,null)||Rd(t)}const Na=new WeakMap;function bD(t,e){if(!Na.has(t)){const n=sy(!0);Na.set(t,n);const{unmount:s}=e;e.unmount=()=>{s.call(e),n.stop(),Na.delete(t)}}return Na.get(t)}const ED=new WeakMap,xa=new WeakMap;function a0(t){const e=vD(t);if(!xa.has(e)){let n;const i=[new Promise(r=>{n=r}),r=>{xa.set(e,r),n(r.value)}];xa.set(e,i)}return xa.get(e)}function WH(t){const e=a0(t);return Array.isArray(e)?e[0]:Promise.resolve(e.value)}function TD(t,e){Wb(e,n=>{const s=a0();t.value=n,Array.isArray(s)&&s[1](t)})}var Vm={};const Wm="@firebase/database",Km="1.0.8";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let l0="";function CD(t){l0=t}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class ID{constructor(e){this.domStorage_=e,this.prefix_="firebase:"}set(e,n){n==null?this.domStorage_.removeItem(this.prefixedName_(e)):this.domStorage_.setItem(this.prefixedName_(e),gt(n))}get(e){const n=this.domStorage_.getItem(this.prefixedName_(e));return n==null?null:bo(n)}remove(e){this.domStorage_.removeItem(this.prefixedName_(e))}prefixedName_(e){return this.prefix_+e}toString(){return this.domStorage_.toString()}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class SD{constructor(){this.cache_={},this.isInMemoryStorage=!0}set(e,n){n==null?delete this.cache_[e]:this.cache_[e]=n}get(e){return us(this.cache_,e)?this.cache_[e]:null}remove(e){delete this.cache_[e]}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const c0=function(t){try{if(typeof window<"u"&&typeof window[t]<"u"){const e=window[t];return e.setItem("firebase:sentinel","cache"),e.removeItem("firebase:sentinel"),new ID(e)}}catch{}return new SD},si=c0("localStorage"),AD=c0("sessionStorage");/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const sr=new Uo("@firebase/database"),kD=function(){let t=1;return function(){return t++}}(),u0=function(t){const e=_k(t),n=new dk;n.update(e);const s=n.digest();return ic.encodeByteArray(s)},Ko=function(...t){let e="";for(let n=0;n<t.length;n++){const s=t[n];Array.isArray(s)||s&&typeof s=="object"&&typeof s.length=="number"?e+=Ko.apply(null,s):typeof s=="object"?e+=gt(s):e+=s,e+=" "}return e};let ao=null,qm=!0;const RD=function(t,e){Y(!e,"Can't turn on custom loggers persistently."),sr.logLevel=Ie.VERBOSE,ao=sr.log.bind(sr)},Tt=function(...t){if(qm===!0&&(qm=!1,ao===null&&AD.get("logging_enabled")===!0&&RD()),ao){const e=Ko.apply(null,t);ao(e)}},qo=function(t){return function(...e){Tt(t,...e)}},xh=function(...t){const e="FIREBASE INTERNAL ERROR: "+Ko(...t);sr.error(e)},gi=function(...t){const e=`FIREBASE FATAL ERROR: ${Ko(...t)}`;throw sr.error(e),new Error(e)},Jt=function(...t){const e="FIREBASE WARNING: "+Ko(...t);sr.warn(e)},PD=function(){typeof window<"u"&&window.location&&window.location.protocol&&window.location.protocol.indexOf("https:")!==-1&&Jt("Insecure Firebase access from a secure page. Please use https in calls to new Firebase().")},h0=function(t){return typeof t=="number"&&(t!==t||t===Number.POSITIVE_INFINITY||t===Number.NEGATIVE_INFINITY)},OD=function(t){if(document.readyState==="complete")t();else{let e=!1;const n=function(){if(!document.body){setTimeout(n,Math.floor(10));return}e||(e=!0,t())};document.addEventListener?(document.addEventListener("DOMContentLoaded",n,!1),window.addEventListener("load",n,!1)):document.attachEvent&&(document.attachEvent("onreadystatechange",()=>{document.readyState==="complete"&&n()}),window.attachEvent("onload",n))}},pr="[MIN_NAME]",mi="[MAX_NAME]",br=function(t,e){if(t===e)return 0;if(t===pr||e===mi)return-1;if(e===pr||t===mi)return 1;{const n=zm(t),s=zm(e);return n!==null?s!==null?n-s===0?t.length-e.length:n-s:-1:s!==null?1:t<e?-1:1}},ND=function(t,e){return t===e?0:t<e?-1:1},Vr=function(t,e){if(e&&t in e)return e[t];throw new Error("Missing required key ("+t+") in object: "+gt(e))},Zd=function(t){if(typeof t!="object"||t===null)return gt(t);const e=[];for(const s in t)e.push(s);e.sort();let n="{";for(let s=0;s<e.length;s++)s!==0&&(n+=","),n+=gt(e[s]),n+=":",n+=Zd(t[e[s]]);return n+="}",n},d0=function(t,e){const n=t.length;if(n<=e)return[t];const s=[];for(let i=0;i<n;i+=e)i+e>n?s.push(t.substring(i,n)):s.push(t.substring(i,i+e));return s};function dn(t,e){for(const n in t)t.hasOwnProperty(n)&&e(n,t[n])}const f0=function(t){Y(!h0(t),"Invalid JSON number");const e=11,n=52,s=(1<<e-1)-1;let i,r,o,l,c;t===0?(r=0,o=0,i=1/t===-1/0?1:0):(i=t<0,t=Math.abs(t),t>=Math.pow(2,1-s)?(l=Math.min(Math.floor(Math.log(t)/Math.LN2),s),r=l+s,o=Math.round(t*Math.pow(2,n-l)-Math.pow(2,n))):(r=0,o=Math.round(t/Math.pow(2,1-s-n))));const u=[];for(c=n;c;c-=1)u.push(o%2?1:0),o=Math.floor(o/2);for(c=e;c;c-=1)u.push(r%2?1:0),r=Math.floor(r/2);u.push(i?1:0),u.reverse();const h=u.join("");let f="";for(c=0;c<64;c+=8){let g=parseInt(h.substr(c,8),2).toString(16);g.length===1&&(g="0"+g),f=f+g}return f.toLowerCase()},xD=function(){return!!(typeof window=="object"&&window.chrome&&window.chrome.extension&&!/^chrome/.test(window.location.href))},DD=function(){return typeof Windows=="object"&&typeof Windows.UI=="object"},LD=new RegExp("^-?(0*)\\d{1,10}$"),MD=-2147483648,FD=2147483647,zm=function(t){if(LD.test(t)){const e=Number(t);if(e>=MD&&e<=FD)return e}return null},zo=function(t){try{t()}catch(e){setTimeout(()=>{const n=e.stack||"";throw Jt("Exception was thrown by user callback.",n),e},Math.floor(0))}},UD=function(){return(typeof window=="object"&&window.navigator&&window.navigator.userAgent||"").search(/googlebot|google webmaster tools|bingbot|yahoo! slurp|baiduspider|yandexbot|duckduckbot/i)>=0},lo=function(t,e){const n=setTimeout(t,e);return typeof n=="number"&&typeof Deno<"u"&&Deno.unrefTimer?Deno.unrefTimer(n):typeof n=="object"&&n.unref&&n.unref(),n};/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class $D{constructor(e,n){this.appName_=e,this.appCheckProvider=n,this.appCheck=n==null?void 0:n.getImmediate({optional:!0}),this.appCheck||n==null||n.get().then(s=>this.appCheck=s)}getToken(e){return this.appCheck?this.appCheck.getToken(e):new Promise((n,s)=>{setTimeout(()=>{this.appCheck?this.getToken(e).then(n,s):n(null)},0)})}addTokenChangeListener(e){var n;(n=this.appCheckProvider)===null||n===void 0||n.get().then(s=>s.addTokenListener(e))}notifyForInvalidToken(){Jt(`Provided AppCheck credentials for the app named "${this.appName_}" are invalid. This usually indicates your app was not initialized correctly.`)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class HD{constructor(e,n,s){this.appName_=e,this.firebaseOptions_=n,this.authProvider_=s,this.auth_=null,this.auth_=s.getImmediate({optional:!0}),this.auth_||s.onInit(i=>this.auth_=i)}getToken(e){return this.auth_?this.auth_.getToken(e).catch(n=>n&&n.code==="auth/token-not-initialized"?(Tt("Got auth/token-not-initialized error.  Treating as null token."),null):Promise.reject(n)):new Promise((n,s)=>{setTimeout(()=>{this.auth_?this.getToken(e).then(n,s):n(null)},0)})}addTokenChangeListener(e){this.auth_?this.auth_.addAuthTokenListener(e):this.authProvider_.get().then(n=>n.addAuthTokenListener(e))}removeTokenChangeListener(e){this.authProvider_.get().then(n=>n.removeAuthTokenListener(e))}notifyForInvalidToken(){let e='Provided authentication credentials for the app named "'+this.appName_+'" are invalid. This usually indicates your app was not initialized correctly. ';"credential"in this.firebaseOptions_?e+='Make sure the "credential" property provided to initializeApp() is authorized to access the specified "databaseURL" and is from the correct project.':"serviceAccount"in this.firebaseOptions_?e+='Make sure the "serviceAccount" property provided to initializeApp() is authorized to access the specified "databaseURL" and is from the correct project.':e+='Make sure the "apiKey" and "databaseURL" properties provided to initializeApp() match the values provided for your app at https://console.firebase.google.com/.',Jt(e)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ef="5",p0="v",g0="s",m0="r",_0="f",y0=/(console\.firebase|firebase-console-\w+\.corp|firebase\.corp)\.google\.com/,w0="ls",v0="p",Dh="ac",b0="websocket",E0="long_polling";/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class BD{constructor(e,n,s,i,r=!1,o="",l=!1,c=!1){this.secure=n,this.namespace=s,this.webSocketOnly=i,this.nodeAdmin=r,this.persistenceKey=o,this.includeNamespaceInQueryParams=l,this.isUsingEmulator=c,this._host=e.toLowerCase(),this._domain=this._host.substr(this._host.indexOf(".")+1),this.internalHost=si.get("host:"+e)||this._host}isCacheableHost(){return this.internalHost.substr(0,2)==="s-"}isCustomHost(){return this._domain!=="firebaseio.com"&&this._domain!=="firebaseio-demo.com"}get host(){return this._host}set host(e){e!==this.internalHost&&(this.internalHost=e,this.isCacheableHost()&&si.set("host:"+this._host,this.internalHost))}toString(){let e=this.toURLString();return this.persistenceKey&&(e+="<"+this.persistenceKey+">"),e}toURLString(){const e=this.secure?"https://":"http://",n=this.includeNamespaceInQueryParams?`?ns=${this.namespace}`:"";return`${e}${this.host}/${n}`}}function jD(t){return t.host!==t.internalHost||t.isCustomHost()||t.includeNamespaceInQueryParams}function T0(t,e,n){Y(typeof e=="string","typeof type must == string"),Y(typeof n=="object","typeof params must == object");let s;if(e===b0)s=(t.secure?"wss://":"ws://")+t.internalHost+"/.ws?";else if(e===E0)s=(t.secure?"https://":"http://")+t.internalHost+"/.lp?";else throw new Error("Unknown connection type: "+e);jD(t)&&(n.ns=t.namespace);const i=[];return dn(n,(r,o)=>{i.push(r+"="+o)}),s+i.join("&")}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class VD{constructor(){this.counters_={}}incrementCounter(e,n=1){us(this.counters_,e)||(this.counters_[e]=0),this.counters_[e]+=n}get(){return XA(this.counters_)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Iu={},Su={};function tf(t){const e=t.toString();return Iu[e]||(Iu[e]=new VD),Iu[e]}function WD(t,e){const n=t.toString();return Su[n]||(Su[n]=e()),Su[n]}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class KD{constructor(e){this.onMessage_=e,this.pendingResponses=[],this.currentResponseNum=0,this.closeAfterResponse=-1,this.onClose=null}closeAfter(e,n){this.closeAfterResponse=e,this.onClose=n,this.closeAfterResponse<this.currentResponseNum&&(this.onClose(),this.onClose=null)}handleResponse(e,n){for(this.pendingResponses[e]=n;this.pendingResponses[this.currentResponseNum];){const s=this.pendingResponses[this.currentResponseNum];delete this.pendingResponses[this.currentResponseNum];for(let i=0;i<s.length;++i)s[i]&&zo(()=>{this.onMessage_(s[i])});if(this.currentResponseNum===this.closeAfterResponse){this.onClose&&(this.onClose(),this.onClose=null);break}this.currentResponseNum++}}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Gm="start",qD="close",zD="pLPCommand",GD="pRTLPCB",C0="id",I0="pw",S0="ser",YD="cb",XD="seg",JD="ts",QD="d",ZD="dframe",A0=1870,k0=30,eL=A0-k0,tL=25e3,nL=3e4;class Wi{constructor(e,n,s,i,r,o,l){this.connId=e,this.repoInfo=n,this.applicationId=s,this.appCheckToken=i,this.authToken=r,this.transportSessionId=o,this.lastSessionId=l,this.bytesSent=0,this.bytesReceived=0,this.everConnected_=!1,this.log_=qo(e),this.stats_=tf(n),this.urlFn=c=>(this.appCheckToken&&(c[Dh]=this.appCheckToken),T0(n,E0,c))}open(e,n){this.curSegmentNum=0,this.onDisconnect_=n,this.myPacketOrderer=new KD(e),this.isClosed_=!1,this.connectTimeoutTimer_=setTimeout(()=>{this.log_("Timed out trying to connect."),this.onClosed_(),this.connectTimeoutTimer_=null},Math.floor(nL)),OD(()=>{if(this.isClosed_)return;this.scriptTagHolder=new nf((...r)=>{const[o,l,c,u,h]=r;if(this.incrementIncomingBytes_(r),!!this.scriptTagHolder)if(this.connectTimeoutTimer_&&(clearTimeout(this.connectTimeoutTimer_),this.connectTimeoutTimer_=null),this.everConnected_=!0,o===Gm)this.id=l,this.password=c;else if(o===qD)l?(this.scriptTagHolder.sendNewPolls=!1,this.myPacketOrderer.closeAfter(l,()=>{this.onClosed_()})):this.onClosed_();else throw new Error("Unrecognized command received: "+o)},(...r)=>{const[o,l]=r;this.incrementIncomingBytes_(r),this.myPacketOrderer.handleResponse(o,l)},()=>{this.onClosed_()},this.urlFn);const s={};s[Gm]="t",s[S0]=Math.floor(Math.random()*1e8),this.scriptTagHolder.uniqueCallbackIdentifier&&(s[YD]=this.scriptTagHolder.uniqueCallbackIdentifier),s[p0]=ef,this.transportSessionId&&(s[g0]=this.transportSessionId),this.lastSessionId&&(s[w0]=this.lastSessionId),this.applicationId&&(s[v0]=this.applicationId),this.appCheckToken&&(s[Dh]=this.appCheckToken),typeof location<"u"&&location.hostname&&y0.test(location.hostname)&&(s[m0]=_0);const i=this.urlFn(s);this.log_("Connecting via long-poll to "+i),this.scriptTagHolder.addTag(i,()=>{})})}start(){this.scriptTagHolder.startLongPoll(this.id,this.password),this.addDisconnectPingFrame(this.id,this.password)}static forceAllow(){Wi.forceAllow_=!0}static forceDisallow(){Wi.forceDisallow_=!0}static isAvailable(){return Wi.forceAllow_?!0:!Wi.forceDisallow_&&typeof document<"u"&&document.createElement!=null&&!xD()&&!DD()}markConnectionHealthy(){}shutdown_(){this.isClosed_=!0,this.scriptTagHolder&&(this.scriptTagHolder.close(),this.scriptTagHolder=null),this.myDisconnFrame&&(document.body.removeChild(this.myDisconnFrame),this.myDisconnFrame=null),this.connectTimeoutTimer_&&(clearTimeout(this.connectTimeoutTimer_),this.connectTimeoutTimer_=null)}onClosed_(){this.isClosed_||(this.log_("Longpoll is closing itself"),this.shutdown_(),this.onDisconnect_&&(this.onDisconnect_(this.everConnected_),this.onDisconnect_=null))}close(){this.isClosed_||(this.log_("Longpoll is being closed."),this.shutdown_())}send(e){const n=gt(e);this.bytesSent+=n.length,this.stats_.incrementCounter("bytes_sent",n.length);const s=zw(n),i=d0(s,eL);for(let r=0;r<i.length;r++)this.scriptTagHolder.enqueueSegment(this.curSegmentNum,i.length,i[r]),this.curSegmentNum++}addDisconnectPingFrame(e,n){this.myDisconnFrame=document.createElement("iframe");const s={};s[ZD]="t",s[C0]=e,s[I0]=n,this.myDisconnFrame.src=this.urlFn(s),this.myDisconnFrame.style.display="none",document.body.appendChild(this.myDisconnFrame)}incrementIncomingBytes_(e){const n=gt(e).length;this.bytesReceived+=n,this.stats_.incrementCounter("bytes_received",n)}}class nf{constructor(e,n,s,i){this.onDisconnect=s,this.urlFn=i,this.outstandingRequests=new Set,this.pendingSegs=[],this.currentSerial=Math.floor(Math.random()*1e8),this.sendNewPolls=!0;{this.uniqueCallbackIdentifier=kD(),window[zD+this.uniqueCallbackIdentifier]=e,window[GD+this.uniqueCallbackIdentifier]=n,this.myIFrame=nf.createIFrame_();let r="";this.myIFrame.src&&this.myIFrame.src.substr(0,11)==="javascript:"&&(r='<script>document.domain="'+document.domain+'";<\/script>');const o="<html><body>"+r+"</body></html>";try{this.myIFrame.doc.open(),this.myIFrame.doc.write(o),this.myIFrame.doc.close()}catch(l){Tt("frame writing exception"),l.stack&&Tt(l.stack),Tt(l)}}}static createIFrame_(){const e=document.createElement("iframe");if(e.style.display="none",document.body){document.body.appendChild(e);try{e.contentWindow.document||Tt("No IE domain setting required")}catch{const s=document.domain;e.src="javascript:void((function(){document.open();document.domain='"+s+"';document.close();})())"}}else throw"Document body has not initialized. Wait to initialize Firebase until after the document is ready.";return e.contentDocument?e.doc=e.contentDocument:e.contentWindow?e.doc=e.contentWindow.document:e.document&&(e.doc=e.document),e}close(){this.alive=!1,this.myIFrame&&(this.myIFrame.doc.body.textContent="",setTimeout(()=>{this.myIFrame!==null&&(document.body.removeChild(this.myIFrame),this.myIFrame=null)},Math.floor(0)));const e=this.onDisconnect;e&&(this.onDisconnect=null,e())}startLongPoll(e,n){for(this.myID=e,this.myPW=n,this.alive=!0;this.newRequest_(););}newRequest_(){if(this.alive&&this.sendNewPolls&&this.outstandingRequests.size<(this.pendingSegs.length>0?2:1)){this.currentSerial++;const e={};e[C0]=this.myID,e[I0]=this.myPW,e[S0]=this.currentSerial;let n=this.urlFn(e),s="",i=0;for(;this.pendingSegs.length>0&&this.pendingSegs[0].d.length+k0+s.length<=A0;){const o=this.pendingSegs.shift();s=s+"&"+XD+i+"="+o.seg+"&"+JD+i+"="+o.ts+"&"+QD+i+"="+o.d,i++}return n=n+s,this.addLongPollTag_(n,this.currentSerial),!0}else return!1}enqueueSegment(e,n,s){this.pendingSegs.push({seg:e,ts:n,d:s}),this.alive&&this.newRequest_()}addLongPollTag_(e,n){this.outstandingRequests.add(n);const s=()=>{this.outstandingRequests.delete(n),this.newRequest_()},i=setTimeout(s,Math.floor(tL)),r=()=>{clearTimeout(i),s()};this.addTag(e,r)}addTag(e,n){setTimeout(()=>{try{if(!this.sendNewPolls)return;const s=this.myIFrame.doc.createElement("script");s.type="text/javascript",s.async=!0,s.src=e,s.onload=s.onreadystatechange=function(){const i=s.readyState;(!i||i==="loaded"||i==="complete")&&(s.onload=s.onreadystatechange=null,s.parentNode&&s.parentNode.removeChild(s),n())},s.onerror=()=>{Tt("Long-poll script failed to load: "+e),this.sendNewPolls=!1,this.close()},this.myIFrame.doc.body.appendChild(s)}catch{}},Math.floor(1))}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const sL=16384,iL=45e3;let Al=null;typeof MozWebSocket<"u"?Al=MozWebSocket:typeof WebSocket<"u"&&(Al=WebSocket);class _n{constructor(e,n,s,i,r,o,l){this.connId=e,this.applicationId=s,this.appCheckToken=i,this.authToken=r,this.keepaliveTimer=null,this.frames=null,this.totalFrames=0,this.bytesSent=0,this.bytesReceived=0,this.log_=qo(this.connId),this.stats_=tf(n),this.connURL=_n.connectionURL_(n,o,l,i,s),this.nodeAdmin=n.nodeAdmin}static connectionURL_(e,n,s,i,r){const o={};return o[p0]=ef,typeof location<"u"&&location.hostname&&y0.test(location.hostname)&&(o[m0]=_0),n&&(o[g0]=n),s&&(o[w0]=s),i&&(o[Dh]=i),r&&(o[v0]=r),T0(e,b0,o)}open(e,n){this.onDisconnect=n,this.onMessage=e,this.log_("Websocket connecting to "+this.connURL),this.everConnected_=!1,si.set("previous_websocket_failure",!0);try{let s;Zw(),this.mySock=new Al(this.connURL,[],s)}catch(s){this.log_("Error instantiating WebSocket.");const i=s.message||s.data;i&&this.log_(i),this.onClosed_();return}this.mySock.onopen=()=>{this.log_("Websocket connected."),this.everConnected_=!0},this.mySock.onclose=()=>{this.log_("Websocket connection was disconnected."),this.mySock=null,this.onClosed_()},this.mySock.onmessage=s=>{this.handleIncomingFrame(s)},this.mySock.onerror=s=>{this.log_("WebSocket error.  Closing connection.");const i=s.message||s.data;i&&this.log_(i),this.onClosed_()}}start(){}static forceDisallow(){_n.forceDisallow_=!0}static isAvailable(){let e=!1;if(typeof navigator<"u"&&navigator.userAgent){const n=/Android ([0-9]{0,}\.[0-9]{0,})/,s=navigator.userAgent.match(n);s&&s.length>1&&parseFloat(s[1])<4.4&&(e=!0)}return!e&&Al!==null&&!_n.forceDisallow_}static previouslyFailed(){return si.isInMemoryStorage||si.get("previous_websocket_failure")===!0}markConnectionHealthy(){si.remove("previous_websocket_failure")}appendFrame_(e){if(this.frames.push(e),this.frames.length===this.totalFrames){const n=this.frames.join("");this.frames=null;const s=bo(n);this.onMessage(s)}}handleNewFrameCount_(e){this.totalFrames=e,this.frames=[]}extractFrameCount_(e){if(Y(this.frames===null,"We already have a frame buffer"),e.length<=6){const n=Number(e);if(!isNaN(n))return this.handleNewFrameCount_(n),null}return this.handleNewFrameCount_(1),e}handleIncomingFrame(e){if(this.mySock===null)return;const n=e.data;if(this.bytesReceived+=n.length,this.stats_.incrementCounter("bytes_received",n.length),this.resetKeepAlive(),this.frames!==null)this.appendFrame_(n);else{const s=this.extractFrameCount_(n);s!==null&&this.appendFrame_(s)}}send(e){this.resetKeepAlive();const n=gt(e);this.bytesSent+=n.length,this.stats_.incrementCounter("bytes_sent",n.length);const s=d0(n,sL);s.length>1&&this.sendString_(String(s.length));for(let i=0;i<s.length;i++)this.sendString_(s[i])}shutdown_(){this.isClosed_=!0,this.keepaliveTimer&&(clearInterval(this.keepaliveTimer),this.keepaliveTimer=null),this.mySock&&(this.mySock.close(),this.mySock=null)}onClosed_(){this.isClosed_||(this.log_("WebSocket is closing itself"),this.shutdown_(),this.onDisconnect&&(this.onDisconnect(this.everConnected_),this.onDisconnect=null))}close(){this.isClosed_||(this.log_("WebSocket is being closed"),this.shutdown_())}resetKeepAlive(){clearInterval(this.keepaliveTimer),this.keepaliveTimer=setInterval(()=>{this.mySock&&this.sendString_("0"),this.resetKeepAlive()},Math.floor(iL))}sendString_(e){try{this.mySock.send(e)}catch(n){this.log_("Exception thrown from WebSocket.send():",n.message||n.data,"Closing connection."),setTimeout(this.onClosed_.bind(this),0)}}}_n.responsesRequiredToBeHealthy=2;_n.healthyTimeout=3e4;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class ko{constructor(e){this.initTransports_(e)}static get ALL_TRANSPORTS(){return[Wi,_n]}static get IS_TRANSPORT_INITIALIZED(){return this.globalTransportInitialized_}initTransports_(e){const n=_n&&_n.isAvailable();let s=n&&!_n.previouslyFailed();if(e.webSocketOnly&&(n||Jt("wss:// URL used, but browser isn't known to support websockets.  Trying anyway."),s=!0),s)this.transports_=[_n];else{const i=this.transports_=[];for(const r of ko.ALL_TRANSPORTS)r&&r.isAvailable()&&i.push(r);ko.globalTransportInitialized_=!0}}initialTransport(){if(this.transports_.length>0)return this.transports_[0];throw new Error("No transports available")}upgradeTransport(){return this.transports_.length>1?this.transports_[1]:null}}ko.globalTransportInitialized_=!1;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const rL=6e4,oL=5e3,aL=10*1024,lL=100*1024,Au="t",Ym="d",cL="s",Xm="r",uL="e",Jm="o",Qm="a",Zm="n",e_="p",hL="h";class dL{constructor(e,n,s,i,r,o,l,c,u,h){this.id=e,this.repoInfo_=n,this.applicationId_=s,this.appCheckToken_=i,this.authToken_=r,this.onMessage_=o,this.onReady_=l,this.onDisconnect_=c,this.onKill_=u,this.lastSessionId=h,this.connectionCount=0,this.pendingDataMessages=[],this.state_=0,this.log_=qo("c:"+this.id+":"),this.transportManager_=new ko(n),this.log_("Connection created"),this.start_()}start_(){const e=this.transportManager_.initialTransport();this.conn_=new e(this.nextTransportId_(),this.repoInfo_,this.applicationId_,this.appCheckToken_,this.authToken_,null,this.lastSessionId),this.primaryResponsesRequired_=e.responsesRequiredToBeHealthy||0;const n=this.connReceiver_(this.conn_),s=this.disconnReceiver_(this.conn_);this.tx_=this.conn_,this.rx_=this.conn_,this.secondaryConn_=null,this.isHealthy_=!1,setTimeout(()=>{this.conn_&&this.conn_.open(n,s)},Math.floor(0));const i=e.healthyTimeout||0;i>0&&(this.healthyTimeout_=lo(()=>{this.healthyTimeout_=null,this.isHealthy_||(this.conn_&&this.conn_.bytesReceived>lL?(this.log_("Connection exceeded healthy timeout but has received "+this.conn_.bytesReceived+" bytes.  Marking connection healthy."),this.isHealthy_=!0,this.conn_.markConnectionHealthy()):this.conn_&&this.conn_.bytesSent>aL?this.log_("Connection exceeded healthy timeout but has sent "+this.conn_.bytesSent+" bytes.  Leaving connection alive."):(this.log_("Closing unhealthy connection after timeout."),this.close()))},Math.floor(i)))}nextTransportId_(){return"c:"+this.id+":"+this.connectionCount++}disconnReceiver_(e){return n=>{e===this.conn_?this.onConnectionLost_(n):e===this.secondaryConn_?(this.log_("Secondary connection lost."),this.onSecondaryConnectionLost_()):this.log_("closing an old connection")}}connReceiver_(e){return n=>{this.state_!==2&&(e===this.rx_?this.onPrimaryMessageReceived_(n):e===this.secondaryConn_?this.onSecondaryMessageReceived_(n):this.log_("message on old connection"))}}sendRequest(e){const n={t:"d",d:e};this.sendData_(n)}tryCleanupConnection(){this.tx_===this.secondaryConn_&&this.rx_===this.secondaryConn_&&(this.log_("cleaning up and promoting a connection: "+this.secondaryConn_.connId),this.conn_=this.secondaryConn_,this.secondaryConn_=null)}onSecondaryControl_(e){if(Au in e){const n=e[Au];n===Qm?this.upgradeIfSecondaryHealthy_():n===Xm?(this.log_("Got a reset on secondary, closing it"),this.secondaryConn_.close(),(this.tx_===this.secondaryConn_||this.rx_===this.secondaryConn_)&&this.close()):n===Jm&&(this.log_("got pong on secondary."),this.secondaryResponsesRequired_--,this.upgradeIfSecondaryHealthy_())}}onSecondaryMessageReceived_(e){const n=Vr("t",e),s=Vr("d",e);if(n==="c")this.onSecondaryControl_(s);else if(n==="d")this.pendingDataMessages.push(s);else throw new Error("Unknown protocol layer: "+n)}upgradeIfSecondaryHealthy_(){this.secondaryResponsesRequired_<=0?(this.log_("Secondary connection is healthy."),this.isHealthy_=!0,this.secondaryConn_.markConnectionHealthy(),this.proceedWithUpgrade_()):(this.log_("sending ping on secondary."),this.secondaryConn_.send({t:"c",d:{t:e_,d:{}}}))}proceedWithUpgrade_(){this.secondaryConn_.start(),this.log_("sending client ack on secondary"),this.secondaryConn_.send({t:"c",d:{t:Qm,d:{}}}),this.log_("Ending transmission on primary"),this.conn_.send({t:"c",d:{t:Zm,d:{}}}),this.tx_=this.secondaryConn_,this.tryCleanupConnection()}onPrimaryMessageReceived_(e){const n=Vr("t",e),s=Vr("d",e);n==="c"?this.onControl_(s):n==="d"&&this.onDataMessage_(s)}onDataMessage_(e){this.onPrimaryResponse_(),this.onMessage_(e)}onPrimaryResponse_(){this.isHealthy_||(this.primaryResponsesRequired_--,this.primaryResponsesRequired_<=0&&(this.log_("Primary connection is healthy."),this.isHealthy_=!0,this.conn_.markConnectionHealthy()))}onControl_(e){const n=Vr(Au,e);if(Ym in e){const s=e[Ym];if(n===hL){const i=Object.assign({},s);this.repoInfo_.isUsingEmulator&&(i.h=this.repoInfo_.host),this.onHandshake_(i)}else if(n===Zm){this.log_("recvd end transmission on primary"),this.rx_=this.secondaryConn_;for(let i=0;i<this.pendingDataMessages.length;++i)this.onDataMessage_(this.pendingDataMessages[i]);this.pendingDataMessages=[],this.tryCleanupConnection()}else n===cL?this.onConnectionShutdown_(s):n===Xm?this.onReset_(s):n===uL?xh("Server Error: "+s):n===Jm?(this.log_("got pong on primary."),this.onPrimaryResponse_(),this.sendPingOnPrimaryIfNecessary_()):xh("Unknown control packet command: "+n)}}onHandshake_(e){const n=e.ts,s=e.v,i=e.h;this.sessionId=e.s,this.repoInfo_.host=i,this.state_===0&&(this.conn_.start(),this.onConnectionEstablished_(this.conn_,n),ef!==s&&Jt("Protocol version mismatch detected"),this.tryStartUpgrade_())}tryStartUpgrade_(){const e=this.transportManager_.upgradeTransport();e&&this.startUpgrade_(e)}startUpgrade_(e){this.secondaryConn_=new e(this.nextTransportId_(),this.repoInfo_,this.applicationId_,this.appCheckToken_,this.authToken_,this.sessionId),this.secondaryResponsesRequired_=e.responsesRequiredToBeHealthy||0;const n=this.connReceiver_(this.secondaryConn_),s=this.disconnReceiver_(this.secondaryConn_);this.secondaryConn_.open(n,s),lo(()=>{this.secondaryConn_&&(this.log_("Timed out trying to upgrade."),this.secondaryConn_.close())},Math.floor(rL))}onReset_(e){this.log_("Reset packet received.  New host: "+e),this.repoInfo_.host=e,this.state_===1?this.close():(this.closeConnections_(),this.start_())}onConnectionEstablished_(e,n){this.log_("Realtime connection established."),this.conn_=e,this.state_=1,this.onReady_&&(this.onReady_(n,this.sessionId),this.onReady_=null),this.primaryResponsesRequired_===0?(this.log_("Primary connection is healthy."),this.isHealthy_=!0):lo(()=>{this.sendPingOnPrimaryIfNecessary_()},Math.floor(oL))}sendPingOnPrimaryIfNecessary_(){!this.isHealthy_&&this.state_===1&&(this.log_("sending ping on primary."),this.sendData_({t:"c",d:{t:e_,d:{}}}))}onSecondaryConnectionLost_(){const e=this.secondaryConn_;this.secondaryConn_=null,(this.tx_===e||this.rx_===e)&&this.close()}onConnectionLost_(e){this.conn_=null,!e&&this.state_===0?(this.log_("Realtime connection failed."),this.repoInfo_.isCacheableHost()&&(si.remove("host:"+this.repoInfo_.host),this.repoInfo_.internalHost=this.repoInfo_.host)):this.state_===1&&this.log_("Realtime connection lost."),this.close()}onConnectionShutdown_(e){this.log_("Connection shutdown command received. Shutting down..."),this.onKill_&&(this.onKill_(e),this.onKill_=null),this.onDisconnect_=null,this.close()}sendData_(e){if(this.state_!==1)throw"Connection is not connected";this.tx_.send(e)}close(){this.state_!==2&&(this.log_("Closing realtime connection."),this.state_=2,this.closeConnections_(),this.onDisconnect_&&(this.onDisconnect_(),this.onDisconnect_=null))}closeConnections_(){this.log_("Shutting down all connections"),this.conn_&&(this.conn_.close(),this.conn_=null),this.secondaryConn_&&(this.secondaryConn_.close(),this.secondaryConn_=null),this.healthyTimeout_&&(clearTimeout(this.healthyTimeout_),this.healthyTimeout_=null)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class R0{put(e,n,s,i){}merge(e,n,s,i){}refreshAuthToken(e){}refreshAppCheckToken(e){}onDisconnectPut(e,n,s){}onDisconnectMerge(e,n,s){}onDisconnectCancel(e,n){}reportStats(e){}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let P0=class{constructor(e){this.allowedEvents_=e,this.listeners_={},Y(Array.isArray(e)&&e.length>0,"Requires a non-empty array")}trigger(e,...n){if(Array.isArray(this.listeners_[e])){const s=[...this.listeners_[e]];for(let i=0;i<s.length;i++)s[i].callback.apply(s[i].context,n)}}on(e,n,s){this.validateEventType_(e),this.listeners_[e]=this.listeners_[e]||[],this.listeners_[e].push({callback:n,context:s});const i=this.getInitialEvent(e);i&&n.apply(s,i)}off(e,n,s){this.validateEventType_(e);const i=this.listeners_[e]||[];for(let r=0;r<i.length;r++)if(i[r].callback===n&&(!s||s===i[r].context)){i.splice(r,1);return}}validateEventType_(e){Y(this.allowedEvents_.find(n=>n===e),"Unknown event: "+e)}};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class kl extends P0{constructor(){super(["online"]),this.online_=!0,typeof window<"u"&&typeof window.addEventListener<"u"&&!Id()&&(window.addEventListener("online",()=>{this.online_||(this.online_=!0,this.trigger("online",!0))},!1),window.addEventListener("offline",()=>{this.online_&&(this.online_=!1,this.trigger("online",!1))},!1))}static getInstance(){return new kl}getInitialEvent(e){return Y(e==="online","Unknown event type: "+e),[this.online_]}currentlyOnline(){return this.online_}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const t_=32,n_=768;class Be{constructor(e,n){if(n===void 0){this.pieces_=e.split("/");let s=0;for(let i=0;i<this.pieces_.length;i++)this.pieces_[i].length>0&&(this.pieces_[s]=this.pieces_[i],s++);this.pieces_.length=s,this.pieceNum_=0}else this.pieces_=e,this.pieceNum_=n}toString(){let e="";for(let n=this.pieceNum_;n<this.pieces_.length;n++)this.pieces_[n]!==""&&(e+="/"+this.pieces_[n]);return e||"/"}}function Oe(){return new Be("")}function me(t){return t.pieceNum_>=t.pieces_.length?null:t.pieces_[t.pieceNum_]}function Fs(t){return t.pieces_.length-t.pieceNum_}function $e(t){let e=t.pieceNum_;return e<t.pieces_.length&&e++,new Be(t.pieces_,e)}function O0(t){return t.pieceNum_<t.pieces_.length?t.pieces_[t.pieces_.length-1]:null}function fL(t){let e="";for(let n=t.pieceNum_;n<t.pieces_.length;n++)t.pieces_[n]!==""&&(e+="/"+encodeURIComponent(String(t.pieces_[n])));return e||"/"}function N0(t,e=0){return t.pieces_.slice(t.pieceNum_+e)}function x0(t){if(t.pieceNum_>=t.pieces_.length)return null;const e=[];for(let n=t.pieceNum_;n<t.pieces_.length-1;n++)e.push(t.pieces_[n]);return new Be(e,0)}function ct(t,e){const n=[];for(let s=t.pieceNum_;s<t.pieces_.length;s++)n.push(t.pieces_[s]);if(e instanceof Be)for(let s=e.pieceNum_;s<e.pieces_.length;s++)n.push(e.pieces_[s]);else{const s=e.split("/");for(let i=0;i<s.length;i++)s[i].length>0&&n.push(s[i])}return new Be(n,0)}function fe(t){return t.pieceNum_>=t.pieces_.length}function ln(t,e){const n=me(t),s=me(e);if(n===null)return e;if(n===s)return ln($e(t),$e(e));throw new Error("INTERNAL ERROR: innerPath ("+e+") is not within outerPath ("+t+")")}function D0(t,e){if(Fs(t)!==Fs(e))return!1;for(let n=t.pieceNum_,s=e.pieceNum_;n<=t.pieces_.length;n++,s++)if(t.pieces_[n]!==e.pieces_[s])return!1;return!0}function yn(t,e){let n=t.pieceNum_,s=e.pieceNum_;if(Fs(t)>Fs(e))return!1;for(;n<t.pieces_.length;){if(t.pieces_[n]!==e.pieces_[s])return!1;++n,++s}return!0}class pL{constructor(e,n){this.errorPrefix_=n,this.parts_=N0(e,0),this.byteLength_=Math.max(1,this.parts_.length);for(let s=0;s<this.parts_.length;s++)this.byteLength_+=rc(this.parts_[s]);L0(this)}}function gL(t,e){t.parts_.length>0&&(t.byteLength_+=1),t.parts_.push(e),t.byteLength_+=rc(e),L0(t)}function mL(t){const e=t.parts_.pop();t.byteLength_-=rc(e),t.parts_.length>0&&(t.byteLength_-=1)}function L0(t){if(t.byteLength_>n_)throw new Error(t.errorPrefix_+"has a key path longer than "+n_+" bytes ("+t.byteLength_+").");if(t.parts_.length>t_)throw new Error(t.errorPrefix_+"path specified exceeds the maximum depth that can be written ("+t_+") or object contains a cycle "+ti(t))}function ti(t){return t.parts_.length===0?"":"in property '"+t.parts_.join(".")+"'"}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class sf extends P0{constructor(){super(["visible"]);let e,n;typeof document<"u"&&typeof document.addEventListener<"u"&&(typeof document.hidden<"u"?(n="visibilitychange",e="hidden"):typeof document.mozHidden<"u"?(n="mozvisibilitychange",e="mozHidden"):typeof document.msHidden<"u"?(n="msvisibilitychange",e="msHidden"):typeof document.webkitHidden<"u"&&(n="webkitvisibilitychange",e="webkitHidden")),this.visible_=!0,n&&document.addEventListener(n,()=>{const s=!document[e];s!==this.visible_&&(this.visible_=s,this.trigger("visible",s))},!1)}static getInstance(){return new sf}getInitialEvent(e){return Y(e==="visible","Unknown event type: "+e),[this.visible_]}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Wr=1e3,_L=60*5*1e3,s_=30*1e3,yL=1.3,wL=3e4,vL="server_kill",i_=3;class ts extends R0{constructor(e,n,s,i,r,o,l,c){if(super(),this.repoInfo_=e,this.applicationId_=n,this.onDataUpdate_=s,this.onConnectStatus_=i,this.onServerInfoUpdate_=r,this.authTokenProvider_=o,this.appCheckTokenProvider_=l,this.authOverride_=c,this.id=ts.nextPersistentConnectionId_++,this.log_=qo("p:"+this.id+":"),this.interruptReasons_={},this.listens=new Map,this.outstandingPuts_=[],this.outstandingGets_=[],this.outstandingPutCount_=0,this.outstandingGetCount_=0,this.onDisconnectRequestQueue_=[],this.connected_=!1,this.reconnectDelay_=Wr,this.maxReconnectDelay_=_L,this.securityDebugCallback_=null,this.lastSessionId=null,this.establishConnectionTimer_=null,this.visible_=!1,this.requestCBHash_={},this.requestNumber_=0,this.realtime_=null,this.authToken_=null,this.appCheckToken_=null,this.forceTokenRefresh_=!1,this.invalidAuthTokenCount_=0,this.invalidAppCheckTokenCount_=0,this.firstConnection_=!0,this.lastConnectionAttemptTime_=null,this.lastConnectionEstablishedTime_=null,c&&!Zw())throw new Error("Auth override specified in options, but not supported on non Node.js platforms");sf.getInstance().on("visible",this.onVisible_,this),e.host.indexOf("fblocal")===-1&&kl.getInstance().on("online",this.onOnline_,this)}sendRequest(e,n,s){const i=++this.requestNumber_,r={r:i,a:e,b:n};this.log_(gt(r)),Y(this.connected_,"sendRequest call when we're not connected not allowed."),this.realtime_.sendRequest(r),s&&(this.requestCBHash_[i]=s)}get(e){this.initConnection_();const n=new vo,i={action:"g",request:{p:e._path.toString(),q:e._queryObject},onComplete:o=>{const l=o.d;o.s==="ok"?n.resolve(l):n.reject(l)}};this.outstandingGets_.push(i),this.outstandingGetCount_++;const r=this.outstandingGets_.length-1;return this.connected_&&this.sendGet_(r),n.promise}listen(e,n,s,i){this.initConnection_();const r=e._queryIdentifier,o=e._path.toString();this.log_("Listen called for "+o+" "+r),this.listens.has(o)||this.listens.set(o,new Map),Y(e._queryParams.isDefault()||!e._queryParams.loadsAllData(),"listen() called for non-default but complete query"),Y(!this.listens.get(o).has(r),"listen() called twice for same path/queryId.");const l={onComplete:i,hashFn:n,query:e,tag:s};this.listens.get(o).set(r,l),this.connected_&&this.sendListen_(l)}sendGet_(e){const n=this.outstandingGets_[e];this.sendRequest("g",n.request,s=>{delete this.outstandingGets_[e],this.outstandingGetCount_--,this.outstandingGetCount_===0&&(this.outstandingGets_=[]),n.onComplete&&n.onComplete(s)})}sendListen_(e){const n=e.query,s=n._path.toString(),i=n._queryIdentifier;this.log_("Listen on "+s+" for "+i);const r={p:s},o="q";e.tag&&(r.q=n._queryObject,r.t=e.tag),r.h=e.hashFn(),this.sendRequest(o,r,l=>{const c=l.d,u=l.s;ts.warnOnListenWarnings_(c,n),(this.listens.get(s)&&this.listens.get(s).get(i))===e&&(this.log_("listen response",l),u!=="ok"&&this.removeListen_(s,i),e.onComplete&&e.onComplete(u,c))})}static warnOnListenWarnings_(e,n){if(e&&typeof e=="object"&&us(e,"w")){const s=ur(e,"w");if(Array.isArray(s)&&~s.indexOf("no_index")){const i='".indexOn": "'+n._queryParams.getIndex().toString()+'"',r=n._path.toString();Jt(`Using an unspecified index. Your data will be downloaded and filtered on the client. Consider adding ${i} at ${r} to your security rules for better performance.`)}}}refreshAuthToken(e){this.authToken_=e,this.log_("Auth token refreshed"),this.authToken_?this.tryAuth():this.connected_&&this.sendRequest("unauth",{},()=>{}),this.reduceReconnectDelayIfAdminCredential_(e)}reduceReconnectDelayIfAdminCredential_(e){(e&&e.length===40||hk(e))&&(this.log_("Admin auth credential detected.  Reducing max reconnect time."),this.maxReconnectDelay_=s_)}refreshAppCheckToken(e){this.appCheckToken_=e,this.log_("App check token refreshed"),this.appCheckToken_?this.tryAppCheck():this.connected_&&this.sendRequest("unappeck",{},()=>{})}tryAuth(){if(this.connected_&&this.authToken_){const e=this.authToken_,n=uk(e)?"auth":"gauth",s={cred:e};this.authOverride_===null?s.noauth=!0:typeof this.authOverride_=="object"&&(s.authvar=this.authOverride_),this.sendRequest(n,s,i=>{const r=i.s,o=i.d||"error";this.authToken_===e&&(r==="ok"?this.invalidAuthTokenCount_=0:this.onAuthRevoked_(r,o))})}}tryAppCheck(){this.connected_&&this.appCheckToken_&&this.sendRequest("appcheck",{token:this.appCheckToken_},e=>{const n=e.s,s=e.d||"error";n==="ok"?this.invalidAppCheckTokenCount_=0:this.onAppCheckRevoked_(n,s)})}unlisten(e,n){const s=e._path.toString(),i=e._queryIdentifier;this.log_("Unlisten called for "+s+" "+i),Y(e._queryParams.isDefault()||!e._queryParams.loadsAllData(),"unlisten() called for non-default but complete query"),this.removeListen_(s,i)&&this.connected_&&this.sendUnlisten_(s,i,e._queryObject,n)}sendUnlisten_(e,n,s,i){this.log_("Unlisten on "+e+" for "+n);const r={p:e},o="n";i&&(r.q=s,r.t=i),this.sendRequest(o,r)}onDisconnectPut(e,n,s){this.initConnection_(),this.connected_?this.sendOnDisconnect_("o",e,n,s):this.onDisconnectRequestQueue_.push({pathString:e,action:"o",data:n,onComplete:s})}onDisconnectMerge(e,n,s){this.initConnection_(),this.connected_?this.sendOnDisconnect_("om",e,n,s):this.onDisconnectRequestQueue_.push({pathString:e,action:"om",data:n,onComplete:s})}onDisconnectCancel(e,n){this.initConnection_(),this.connected_?this.sendOnDisconnect_("oc",e,null,n):this.onDisconnectRequestQueue_.push({pathString:e,action:"oc",data:null,onComplete:n})}sendOnDisconnect_(e,n,s,i){const r={p:n,d:s};this.log_("onDisconnect "+e,r),this.sendRequest(e,r,o=>{i&&setTimeout(()=>{i(o.s,o.d)},Math.floor(0))})}put(e,n,s,i){this.putInternal("p",e,n,s,i)}merge(e,n,s,i){this.putInternal("m",e,n,s,i)}putInternal(e,n,s,i,r){this.initConnection_();const o={p:n,d:s};r!==void 0&&(o.h=r),this.outstandingPuts_.push({action:e,request:o,onComplete:i}),this.outstandingPutCount_++;const l=this.outstandingPuts_.length-1;this.connected_?this.sendPut_(l):this.log_("Buffering put: "+n)}sendPut_(e){const n=this.outstandingPuts_[e].action,s=this.outstandingPuts_[e].request,i=this.outstandingPuts_[e].onComplete;this.outstandingPuts_[e].queued=this.connected_,this.sendRequest(n,s,r=>{this.log_(n+" response",r),delete this.outstandingPuts_[e],this.outstandingPutCount_--,this.outstandingPutCount_===0&&(this.outstandingPuts_=[]),i&&i(r.s,r.d)})}reportStats(e){if(this.connected_){const n={c:e};this.log_("reportStats",n),this.sendRequest("s",n,s=>{if(s.s!=="ok"){const r=s.d;this.log_("reportStats","Error sending stats: "+r)}})}}onDataMessage_(e){if("r"in e){this.log_("from server: "+gt(e));const n=e.r,s=this.requestCBHash_[n];s&&(delete this.requestCBHash_[n],s(e.b))}else{if("error"in e)throw"A server-side error has occurred: "+e.error;"a"in e&&this.onDataPush_(e.a,e.b)}}onDataPush_(e,n){this.log_("handleServerMessage",e,n),e==="d"?this.onDataUpdate_(n.p,n.d,!1,n.t):e==="m"?this.onDataUpdate_(n.p,n.d,!0,n.t):e==="c"?this.onListenRevoked_(n.p,n.q):e==="ac"?this.onAuthRevoked_(n.s,n.d):e==="apc"?this.onAppCheckRevoked_(n.s,n.d):e==="sd"?this.onSecurityDebugPacket_(n):xh("Unrecognized action received from server: "+gt(e)+`
Are you using the latest client?`)}onReady_(e,n){this.log_("connection ready"),this.connected_=!0,this.lastConnectionEstablishedTime_=new Date().getTime(),this.handleTimestamp_(e),this.lastSessionId=n,this.firstConnection_&&this.sendConnectStats_(),this.restoreState_(),this.firstConnection_=!1,this.onConnectStatus_(!0)}scheduleConnect_(e){Y(!this.realtime_,"Scheduling a connect when we're already connected/ing?"),this.establishConnectionTimer_&&clearTimeout(this.establishConnectionTimer_),this.establishConnectionTimer_=setTimeout(()=>{this.establishConnectionTimer_=null,this.establishConnection_()},Math.floor(e))}initConnection_(){!this.realtime_&&this.firstConnection_&&this.scheduleConnect_(0)}onVisible_(e){e&&!this.visible_&&this.reconnectDelay_===this.maxReconnectDelay_&&(this.log_("Window became visible.  Reducing delay."),this.reconnectDelay_=Wr,this.realtime_||this.scheduleConnect_(0)),this.visible_=e}onOnline_(e){e?(this.log_("Browser went online."),this.reconnectDelay_=Wr,this.realtime_||this.scheduleConnect_(0)):(this.log_("Browser went offline.  Killing connection."),this.realtime_&&this.realtime_.close())}onRealtimeDisconnect_(){if(this.log_("data client disconnected"),this.connected_=!1,this.realtime_=null,this.cancelSentTransactions_(),this.requestCBHash_={},this.shouldReconnect_()){this.visible_?this.lastConnectionEstablishedTime_&&(new Date().getTime()-this.lastConnectionEstablishedTime_>wL&&(this.reconnectDelay_=Wr),this.lastConnectionEstablishedTime_=null):(this.log_("Window isn't visible.  Delaying reconnect."),this.reconnectDelay_=this.maxReconnectDelay_,this.lastConnectionAttemptTime_=new Date().getTime());const e=new Date().getTime()-this.lastConnectionAttemptTime_;let n=Math.max(0,this.reconnectDelay_-e);n=Math.random()*n,this.log_("Trying to reconnect in "+n+"ms"),this.scheduleConnect_(n),this.reconnectDelay_=Math.min(this.maxReconnectDelay_,this.reconnectDelay_*yL)}this.onConnectStatus_(!1)}async establishConnection_(){if(this.shouldReconnect_()){this.log_("Making a connection attempt"),this.lastConnectionAttemptTime_=new Date().getTime(),this.lastConnectionEstablishedTime_=null;const e=this.onDataMessage_.bind(this),n=this.onReady_.bind(this),s=this.onRealtimeDisconnect_.bind(this),i=this.id+":"+ts.nextConnectionId_++,r=this.lastSessionId;let o=!1,l=null;const c=function(){l?l.close():(o=!0,s())},u=function(f){Y(l,"sendRequest call when we're not connected not allowed."),l.sendRequest(f)};this.realtime_={close:c,sendRequest:u};const h=this.forceTokenRefresh_;this.forceTokenRefresh_=!1;try{const[f,g]=await Promise.all([this.authTokenProvider_.getToken(h),this.appCheckTokenProvider_.getToken(h)]);o?Tt("getToken() completed but was canceled"):(Tt("getToken() completed. Creating connection."),this.authToken_=f&&f.accessToken,this.appCheckToken_=g&&g.token,l=new dL(i,this.repoInfo_,this.applicationId_,this.appCheckToken_,this.authToken_,e,n,s,m=>{Jt(m+" ("+this.repoInfo_.toString()+")"),this.interrupt(vL)},r))}catch(f){this.log_("Failed to get token: "+f),o||(this.repoInfo_.nodeAdmin&&Jt(f),c())}}}interrupt(e){Tt("Interrupting connection for reason: "+e),this.interruptReasons_[e]=!0,this.realtime_?this.realtime_.close():(this.establishConnectionTimer_&&(clearTimeout(this.establishConnectionTimer_),this.establishConnectionTimer_=null),this.connected_&&this.onRealtimeDisconnect_())}resume(e){Tt("Resuming connection for reason: "+e),delete this.interruptReasons_[e],lh(this.interruptReasons_)&&(this.reconnectDelay_=Wr,this.realtime_||this.scheduleConnect_(0))}handleTimestamp_(e){const n=e-new Date().getTime();this.onServerInfoUpdate_({serverTimeOffset:n})}cancelSentTransactions_(){for(let e=0;e<this.outstandingPuts_.length;e++){const n=this.outstandingPuts_[e];n&&"h"in n.request&&n.queued&&(n.onComplete&&n.onComplete("disconnect"),delete this.outstandingPuts_[e],this.outstandingPutCount_--)}this.outstandingPutCount_===0&&(this.outstandingPuts_=[])}onListenRevoked_(e,n){let s;n?s=n.map(r=>Zd(r)).join("$"):s="default";const i=this.removeListen_(e,s);i&&i.onComplete&&i.onComplete("permission_denied")}removeListen_(e,n){const s=new Be(e).toString();let i;if(this.listens.has(s)){const r=this.listens.get(s);i=r.get(n),r.delete(n),r.size===0&&this.listens.delete(s)}else i=void 0;return i}onAuthRevoked_(e,n){Tt("Auth token revoked: "+e+"/"+n),this.authToken_=null,this.forceTokenRefresh_=!0,this.realtime_.close(),(e==="invalid_token"||e==="permission_denied")&&(this.invalidAuthTokenCount_++,this.invalidAuthTokenCount_>=i_&&(this.reconnectDelay_=s_,this.authTokenProvider_.notifyForInvalidToken()))}onAppCheckRevoked_(e,n){Tt("App check token revoked: "+e+"/"+n),this.appCheckToken_=null,this.forceTokenRefresh_=!0,(e==="invalid_token"||e==="permission_denied")&&(this.invalidAppCheckTokenCount_++,this.invalidAppCheckTokenCount_>=i_&&this.appCheckTokenProvider_.notifyForInvalidToken())}onSecurityDebugPacket_(e){this.securityDebugCallback_?this.securityDebugCallback_(e):"msg"in e&&console.log("FIREBASE: "+e.msg.replace(`
`,`
FIREBASE: `))}restoreState_(){this.tryAuth(),this.tryAppCheck();for(const e of this.listens.values())for(const n of e.values())this.sendListen_(n);for(let e=0;e<this.outstandingPuts_.length;e++)this.outstandingPuts_[e]&&this.sendPut_(e);for(;this.onDisconnectRequestQueue_.length;){const e=this.onDisconnectRequestQueue_.shift();this.sendOnDisconnect_(e.action,e.pathString,e.data,e.onComplete)}for(let e=0;e<this.outstandingGets_.length;e++)this.outstandingGets_[e]&&this.sendGet_(e)}sendConnectStats_(){const e={};let n="js";e["sdk."+n+"."+l0.replace(/\./g,"-")]=1,Id()?e["framework.cordova"]=1:Qw()&&(e["framework.reactnative"]=1),this.reportStats(e)}shouldReconnect_(){const e=kl.getInstance().currentlyOnline();return lh(this.interruptReasons_)&&e}}ts.nextPersistentConnectionId_=0;ts.nextConnectionId_=0;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class _e{constructor(e,n){this.name=e,this.node=n}static Wrap(e,n){return new _e(e,n)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class mc{getCompare(){return this.compare.bind(this)}indexedValueChanged(e,n){const s=new _e(pr,e),i=new _e(pr,n);return this.compare(s,i)!==0}minPost(){return _e.MIN}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let Da;class M0 extends mc{static get __EMPTY_NODE(){return Da}static set __EMPTY_NODE(e){Da=e}compare(e,n){return br(e.name,n.name)}isDefinedOn(e){throw wr("KeyIndex.isDefinedOn not expected to be called.")}indexedValueChanged(e,n){return!1}minPost(){return _e.MIN}maxPost(){return new _e(mi,Da)}makePost(e,n){return Y(typeof e=="string","KeyIndex indexValue must always be a string."),new _e(e,Da)}toString(){return".key"}}const ir=new M0;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class La{constructor(e,n,s,i,r=null){this.isReverse_=i,this.resultGenerator_=r,this.nodeStack_=[];let o=1;for(;!e.isEmpty();)if(e=e,o=n?s(e.key,n):1,i&&(o*=-1),o<0)this.isReverse_?e=e.left:e=e.right;else if(o===0){this.nodeStack_.push(e);break}else this.nodeStack_.push(e),this.isReverse_?e=e.right:e=e.left}getNext(){if(this.nodeStack_.length===0)return null;let e=this.nodeStack_.pop(),n;if(this.resultGenerator_?n=this.resultGenerator_(e.key,e.value):n={key:e.key,value:e.value},this.isReverse_)for(e=e.left;!e.isEmpty();)this.nodeStack_.push(e),e=e.right;else for(e=e.right;!e.isEmpty();)this.nodeStack_.push(e),e=e.left;return n}hasNext(){return this.nodeStack_.length>0}peek(){if(this.nodeStack_.length===0)return null;const e=this.nodeStack_[this.nodeStack_.length-1];return this.resultGenerator_?this.resultGenerator_(e.key,e.value):{key:e.key,value:e.value}}}class lt{constructor(e,n,s,i,r){this.key=e,this.value=n,this.color=s??lt.RED,this.left=i??Vt.EMPTY_NODE,this.right=r??Vt.EMPTY_NODE}copy(e,n,s,i,r){return new lt(e??this.key,n??this.value,s??this.color,i??this.left,r??this.right)}count(){return this.left.count()+1+this.right.count()}isEmpty(){return!1}inorderTraversal(e){return this.left.inorderTraversal(e)||!!e(this.key,this.value)||this.right.inorderTraversal(e)}reverseTraversal(e){return this.right.reverseTraversal(e)||e(this.key,this.value)||this.left.reverseTraversal(e)}min_(){return this.left.isEmpty()?this:this.left.min_()}minKey(){return this.min_().key}maxKey(){return this.right.isEmpty()?this.key:this.right.maxKey()}insert(e,n,s){let i=this;const r=s(e,i.key);return r<0?i=i.copy(null,null,null,i.left.insert(e,n,s),null):r===0?i=i.copy(null,n,null,null,null):i=i.copy(null,null,null,null,i.right.insert(e,n,s)),i.fixUp_()}removeMin_(){if(this.left.isEmpty())return Vt.EMPTY_NODE;let e=this;return!e.left.isRed_()&&!e.left.left.isRed_()&&(e=e.moveRedLeft_()),e=e.copy(null,null,null,e.left.removeMin_(),null),e.fixUp_()}remove(e,n){let s,i;if(s=this,n(e,s.key)<0)!s.left.isEmpty()&&!s.left.isRed_()&&!s.left.left.isRed_()&&(s=s.moveRedLeft_()),s=s.copy(null,null,null,s.left.remove(e,n),null);else{if(s.left.isRed_()&&(s=s.rotateRight_()),!s.right.isEmpty()&&!s.right.isRed_()&&!s.right.left.isRed_()&&(s=s.moveRedRight_()),n(e,s.key)===0){if(s.right.isEmpty())return Vt.EMPTY_NODE;i=s.right.min_(),s=s.copy(i.key,i.value,null,null,s.right.removeMin_())}s=s.copy(null,null,null,null,s.right.remove(e,n))}return s.fixUp_()}isRed_(){return this.color}fixUp_(){let e=this;return e.right.isRed_()&&!e.left.isRed_()&&(e=e.rotateLeft_()),e.left.isRed_()&&e.left.left.isRed_()&&(e=e.rotateRight_()),e.left.isRed_()&&e.right.isRed_()&&(e=e.colorFlip_()),e}moveRedLeft_(){let e=this.colorFlip_();return e.right.left.isRed_()&&(e=e.copy(null,null,null,null,e.right.rotateRight_()),e=e.rotateLeft_(),e=e.colorFlip_()),e}moveRedRight_(){let e=this.colorFlip_();return e.left.left.isRed_()&&(e=e.rotateRight_(),e=e.colorFlip_()),e}rotateLeft_(){const e=this.copy(null,null,lt.RED,null,this.right.left);return this.right.copy(null,null,this.color,e,null)}rotateRight_(){const e=this.copy(null,null,lt.RED,this.left.right,null);return this.left.copy(null,null,this.color,null,e)}colorFlip_(){const e=this.left.copy(null,null,!this.left.color,null,null),n=this.right.copy(null,null,!this.right.color,null,null);return this.copy(null,null,!this.color,e,n)}checkMaxDepth_(){const e=this.check_();return Math.pow(2,e)<=this.count()+1}check_(){if(this.isRed_()&&this.left.isRed_())throw new Error("Red node has red child("+this.key+","+this.value+")");if(this.right.isRed_())throw new Error("Right child of ("+this.key+","+this.value+") is red");const e=this.left.check_();if(e!==this.right.check_())throw new Error("Black depths differ");return e+(this.isRed_()?0:1)}}lt.RED=!0;lt.BLACK=!1;class bL{copy(e,n,s,i,r){return this}insert(e,n,s){return new lt(e,n,null)}remove(e,n){return this}count(){return 0}isEmpty(){return!0}inorderTraversal(e){return!1}reverseTraversal(e){return!1}minKey(){return null}maxKey(){return null}check_(){return 0}isRed_(){return!1}}class Vt{constructor(e,n=Vt.EMPTY_NODE){this.comparator_=e,this.root_=n}insert(e,n){return new Vt(this.comparator_,this.root_.insert(e,n,this.comparator_).copy(null,null,lt.BLACK,null,null))}remove(e){return new Vt(this.comparator_,this.root_.remove(e,this.comparator_).copy(null,null,lt.BLACK,null,null))}get(e){let n,s=this.root_;for(;!s.isEmpty();){if(n=this.comparator_(e,s.key),n===0)return s.value;n<0?s=s.left:n>0&&(s=s.right)}return null}getPredecessorKey(e){let n,s=this.root_,i=null;for(;!s.isEmpty();)if(n=this.comparator_(e,s.key),n===0){if(s.left.isEmpty())return i?i.key:null;for(s=s.left;!s.right.isEmpty();)s=s.right;return s.key}else n<0?s=s.left:n>0&&(i=s,s=s.right);throw new Error("Attempted to find predecessor key for a nonexistent key.  What gives?")}isEmpty(){return this.root_.isEmpty()}count(){return this.root_.count()}minKey(){return this.root_.minKey()}maxKey(){return this.root_.maxKey()}inorderTraversal(e){return this.root_.inorderTraversal(e)}reverseTraversal(e){return this.root_.reverseTraversal(e)}getIterator(e){return new La(this.root_,null,this.comparator_,!1,e)}getIteratorFrom(e,n){return new La(this.root_,e,this.comparator_,!1,n)}getReverseIteratorFrom(e,n){return new La(this.root_,e,this.comparator_,!0,n)}getReverseIterator(e){return new La(this.root_,null,this.comparator_,!0,e)}}Vt.EMPTY_NODE=new bL;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function EL(t,e){return br(t.name,e.name)}function rf(t,e){return br(t,e)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let Lh;function TL(t){Lh=t}const F0=function(t){return typeof t=="number"?"number:"+f0(t):"string:"+t},U0=function(t){if(t.isLeafNode()){const e=t.val();Y(typeof e=="string"||typeof e=="number"||typeof e=="object"&&us(e,".sv"),"Priority must be a string or number.")}else Y(t===Lh||t.isEmpty(),"priority of unexpected type.");Y(t===Lh||t.getPriority().isEmpty(),"Priority nodes can't have a priority of their own.")};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let r_;class at{constructor(e,n=at.__childrenNodeConstructor.EMPTY_NODE){this.value_=e,this.priorityNode_=n,this.lazyHash_=null,Y(this.value_!==void 0&&this.value_!==null,"LeafNode shouldn't be created with null/undefined value."),U0(this.priorityNode_)}static set __childrenNodeConstructor(e){r_=e}static get __childrenNodeConstructor(){return r_}isLeafNode(){return!0}getPriority(){return this.priorityNode_}updatePriority(e){return new at(this.value_,e)}getImmediateChild(e){return e===".priority"?this.priorityNode_:at.__childrenNodeConstructor.EMPTY_NODE}getChild(e){return fe(e)?this:me(e)===".priority"?this.priorityNode_:at.__childrenNodeConstructor.EMPTY_NODE}hasChild(){return!1}getPredecessorChildName(e,n){return null}updateImmediateChild(e,n){return e===".priority"?this.updatePriority(n):n.isEmpty()&&e!==".priority"?this:at.__childrenNodeConstructor.EMPTY_NODE.updateImmediateChild(e,n).updatePriority(this.priorityNode_)}updateChild(e,n){const s=me(e);return s===null?n:n.isEmpty()&&s!==".priority"?this:(Y(s!==".priority"||Fs(e)===1,".priority must be the last token in a path"),this.updateImmediateChild(s,at.__childrenNodeConstructor.EMPTY_NODE.updateChild($e(e),n)))}isEmpty(){return!1}numChildren(){return 0}forEachChild(e,n){return!1}val(e){return e&&!this.getPriority().isEmpty()?{".value":this.getValue(),".priority":this.getPriority().val()}:this.getValue()}hash(){if(this.lazyHash_===null){let e="";this.priorityNode_.isEmpty()||(e+="priority:"+F0(this.priorityNode_.val())+":");const n=typeof this.value_;e+=n+":",n==="number"?e+=f0(this.value_):e+=this.value_,this.lazyHash_=u0(e)}return this.lazyHash_}getValue(){return this.value_}compareTo(e){return e===at.__childrenNodeConstructor.EMPTY_NODE?1:e instanceof at.__childrenNodeConstructor?-1:(Y(e.isLeafNode(),"Unknown node type"),this.compareToLeafNode_(e))}compareToLeafNode_(e){const n=typeof e.value_,s=typeof this.value_,i=at.VALUE_TYPE_ORDER.indexOf(n),r=at.VALUE_TYPE_ORDER.indexOf(s);return Y(i>=0,"Unknown leaf type: "+n),Y(r>=0,"Unknown leaf type: "+s),i===r?s==="object"?0:this.value_<e.value_?-1:this.value_===e.value_?0:1:r-i}withIndex(){return this}isIndexed(){return!0}equals(e){if(e===this)return!0;if(e.isLeafNode()){const n=e;return this.value_===n.value_&&this.priorityNode_.equals(n.priorityNode_)}else return!1}}at.VALUE_TYPE_ORDER=["object","boolean","number","string"];/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let $0,H0;function CL(t){$0=t}function IL(t){H0=t}class SL extends mc{compare(e,n){const s=e.node.getPriority(),i=n.node.getPriority(),r=s.compareTo(i);return r===0?br(e.name,n.name):r}isDefinedOn(e){return!e.getPriority().isEmpty()}indexedValueChanged(e,n){return!e.getPriority().equals(n.getPriority())}minPost(){return _e.MIN}maxPost(){return new _e(mi,new at("[PRIORITY-POST]",H0))}makePost(e,n){const s=$0(e);return new _e(n,new at("[PRIORITY-POST]",s))}toString(){return".priority"}}const It=new SL;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const AL=Math.log(2);class kL{constructor(e){const n=r=>parseInt(Math.log(r)/AL,10),s=r=>parseInt(Array(r+1).join("1"),2);this.count=n(e+1),this.current_=this.count-1;const i=s(this.count);this.bits_=e+1&i}nextBitIsOne(){const e=!(this.bits_&1<<this.current_);return this.current_--,e}}const Rl=function(t,e,n,s){t.sort(e);const i=function(c,u){const h=u-c;let f,g;if(h===0)return null;if(h===1)return f=t[c],g=n?n(f):f,new lt(g,f.node,lt.BLACK,null,null);{const m=parseInt(h/2,10)+c,I=i(c,m),P=i(m+1,u);return f=t[m],g=n?n(f):f,new lt(g,f.node,lt.BLACK,I,P)}},r=function(c){let u=null,h=null,f=t.length;const g=function(I,P){const D=f-I,M=f;f-=I;const x=i(D+1,M),b=t[D],R=n?n(b):b;m(new lt(R,b.node,P,null,x))},m=function(I){u?(u.left=I,u=I):(h=I,u=I)};for(let I=0;I<c.count;++I){const P=c.nextBitIsOne(),D=Math.pow(2,c.count-(I+1));P?g(D,lt.BLACK):(g(D,lt.BLACK),g(D,lt.RED))}return h},o=new kL(t.length),l=r(o);return new Vt(s||e,l)};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let ku;const Fi={};class Qn{constructor(e,n){this.indexes_=e,this.indexSet_=n}static get Default(){return Y(Fi&&It,"ChildrenNode.ts has not been loaded"),ku=ku||new Qn({".priority":Fi},{".priority":It}),ku}get(e){const n=ur(this.indexes_,e);if(!n)throw new Error("No index defined for "+e);return n instanceof Vt?n:null}hasIndex(e){return us(this.indexSet_,e.toString())}addIndex(e,n){Y(e!==ir,"KeyIndex always exists and isn't meant to be added to the IndexMap.");const s=[];let i=!1;const r=n.getIterator(_e.Wrap);let o=r.getNext();for(;o;)i=i||e.isDefinedOn(o.node),s.push(o),o=r.getNext();let l;i?l=Rl(s,e.getCompare()):l=Fi;const c=e.toString(),u=Object.assign({},this.indexSet_);u[c]=e;const h=Object.assign({},this.indexes_);return h[c]=l,new Qn(h,u)}addToIndexes(e,n){const s=hl(this.indexes_,(i,r)=>{const o=ur(this.indexSet_,r);if(Y(o,"Missing index implementation for "+r),i===Fi)if(o.isDefinedOn(e.node)){const l=[],c=n.getIterator(_e.Wrap);let u=c.getNext();for(;u;)u.name!==e.name&&l.push(u),u=c.getNext();return l.push(e),Rl(l,o.getCompare())}else return Fi;else{const l=n.get(e.name);let c=i;return l&&(c=c.remove(new _e(e.name,l))),c.insert(e,e.node)}});return new Qn(s,this.indexSet_)}removeFromIndexes(e,n){const s=hl(this.indexes_,i=>{if(i===Fi)return i;{const r=n.get(e.name);return r?i.remove(new _e(e.name,r)):i}});return new Qn(s,this.indexSet_)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let Kr;class Se{constructor(e,n,s){this.children_=e,this.priorityNode_=n,this.indexMap_=s,this.lazyHash_=null,this.priorityNode_&&U0(this.priorityNode_),this.children_.isEmpty()&&Y(!this.priorityNode_||this.priorityNode_.isEmpty(),"An empty node cannot have a priority")}static get EMPTY_NODE(){return Kr||(Kr=new Se(new Vt(rf),null,Qn.Default))}isLeafNode(){return!1}getPriority(){return this.priorityNode_||Kr}updatePriority(e){return this.children_.isEmpty()?this:new Se(this.children_,e,this.indexMap_)}getImmediateChild(e){if(e===".priority")return this.getPriority();{const n=this.children_.get(e);return n===null?Kr:n}}getChild(e){const n=me(e);return n===null?this:this.getImmediateChild(n).getChild($e(e))}hasChild(e){return this.children_.get(e)!==null}updateImmediateChild(e,n){if(Y(n,"We should always be passing snapshot nodes"),e===".priority")return this.updatePriority(n);{const s=new _e(e,n);let i,r;n.isEmpty()?(i=this.children_.remove(e),r=this.indexMap_.removeFromIndexes(s,this.children_)):(i=this.children_.insert(e,n),r=this.indexMap_.addToIndexes(s,this.children_));const o=i.isEmpty()?Kr:this.priorityNode_;return new Se(i,o,r)}}updateChild(e,n){const s=me(e);if(s===null)return n;{Y(me(e)!==".priority"||Fs(e)===1,".priority must be the last token in a path");const i=this.getImmediateChild(s).updateChild($e(e),n);return this.updateImmediateChild(s,i)}}isEmpty(){return this.children_.isEmpty()}numChildren(){return this.children_.count()}val(e){if(this.isEmpty())return null;const n={};let s=0,i=0,r=!0;if(this.forEachChild(It,(o,l)=>{n[o]=l.val(e),s++,r&&Se.INTEGER_REGEXP_.test(o)?i=Math.max(i,Number(o)):r=!1}),!e&&r&&i<2*s){const o=[];for(const l in n)o[l]=n[l];return o}else return e&&!this.getPriority().isEmpty()&&(n[".priority"]=this.getPriority().val()),n}hash(){if(this.lazyHash_===null){let e="";this.getPriority().isEmpty()||(e+="priority:"+F0(this.getPriority().val())+":"),this.forEachChild(It,(n,s)=>{const i=s.hash();i!==""&&(e+=":"+n+":"+i)}),this.lazyHash_=e===""?"":u0(e)}return this.lazyHash_}getPredecessorChildName(e,n,s){const i=this.resolveIndex_(s);if(i){const r=i.getPredecessorKey(new _e(e,n));return r?r.name:null}else return this.children_.getPredecessorKey(e)}getFirstChildName(e){const n=this.resolveIndex_(e);if(n){const s=n.minKey();return s&&s.name}else return this.children_.minKey()}getFirstChild(e){const n=this.getFirstChildName(e);return n?new _e(n,this.children_.get(n)):null}getLastChildName(e){const n=this.resolveIndex_(e);if(n){const s=n.maxKey();return s&&s.name}else return this.children_.maxKey()}getLastChild(e){const n=this.getLastChildName(e);return n?new _e(n,this.children_.get(n)):null}forEachChild(e,n){const s=this.resolveIndex_(e);return s?s.inorderTraversal(i=>n(i.name,i.node)):this.children_.inorderTraversal(n)}getIterator(e){return this.getIteratorFrom(e.minPost(),e)}getIteratorFrom(e,n){const s=this.resolveIndex_(n);if(s)return s.getIteratorFrom(e,i=>i);{const i=this.children_.getIteratorFrom(e.name,_e.Wrap);let r=i.peek();for(;r!=null&&n.compare(r,e)<0;)i.getNext(),r=i.peek();return i}}getReverseIterator(e){return this.getReverseIteratorFrom(e.maxPost(),e)}getReverseIteratorFrom(e,n){const s=this.resolveIndex_(n);if(s)return s.getReverseIteratorFrom(e,i=>i);{const i=this.children_.getReverseIteratorFrom(e.name,_e.Wrap);let r=i.peek();for(;r!=null&&n.compare(r,e)>0;)i.getNext(),r=i.peek();return i}}compareTo(e){return this.isEmpty()?e.isEmpty()?0:-1:e.isLeafNode()||e.isEmpty()?1:e===Go?-1:0}withIndex(e){if(e===ir||this.indexMap_.hasIndex(e))return this;{const n=this.indexMap_.addIndex(e,this.children_);return new Se(this.children_,this.priorityNode_,n)}}isIndexed(e){return e===ir||this.indexMap_.hasIndex(e)}equals(e){if(e===this)return!0;if(e.isLeafNode())return!1;{const n=e;if(this.getPriority().equals(n.getPriority()))if(this.children_.count()===n.children_.count()){const s=this.getIterator(It),i=n.getIterator(It);let r=s.getNext(),o=i.getNext();for(;r&&o;){if(r.name!==o.name||!r.node.equals(o.node))return!1;r=s.getNext(),o=i.getNext()}return r===null&&o===null}else return!1;else return!1}}resolveIndex_(e){return e===ir?null:this.indexMap_.get(e.toString())}}Se.INTEGER_REGEXP_=/^(0|[1-9]\d*)$/;class RL extends Se{constructor(){super(new Vt(rf),Se.EMPTY_NODE,Qn.Default)}compareTo(e){return e===this?0:1}equals(e){return e===this}getPriority(){return this}getImmediateChild(e){return Se.EMPTY_NODE}isEmpty(){return!1}}const Go=new RL;Object.defineProperties(_e,{MIN:{value:new _e(pr,Se.EMPTY_NODE)},MAX:{value:new _e(mi,Go)}});M0.__EMPTY_NODE=Se.EMPTY_NODE;at.__childrenNodeConstructor=Se;TL(Go);IL(Go);/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const PL=!0;function Ct(t,e=null){if(t===null)return Se.EMPTY_NODE;if(typeof t=="object"&&".priority"in t&&(e=t[".priority"]),Y(e===null||typeof e=="string"||typeof e=="number"||typeof e=="object"&&".sv"in e,"Invalid priority type found: "+typeof e),typeof t=="object"&&".value"in t&&t[".value"]!==null&&(t=t[".value"]),typeof t!="object"||".sv"in t){const n=t;return new at(n,Ct(e))}if(!(t instanceof Array)&&PL){const n=[];let s=!1;if(dn(t,(o,l)=>{if(o.substring(0,1)!=="."){const c=Ct(l);c.isEmpty()||(s=s||!c.getPriority().isEmpty(),n.push(new _e(o,c)))}}),n.length===0)return Se.EMPTY_NODE;const r=Rl(n,EL,o=>o.name,rf);if(s){const o=Rl(n,It.getCompare());return new Se(r,Ct(e),new Qn({".priority":o},{".priority":It}))}else return new Se(r,Ct(e),Qn.Default)}else{let n=Se.EMPTY_NODE;return dn(t,(s,i)=>{if(us(t,s)&&s.substring(0,1)!=="."){const r=Ct(i);(r.isLeafNode()||!r.isEmpty())&&(n=n.updateImmediateChild(s,r))}}),n.updatePriority(Ct(e))}}CL(Ct);/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class OL extends mc{constructor(e){super(),this.indexPath_=e,Y(!fe(e)&&me(e)!==".priority","Can't create PathIndex with empty path or .priority key")}extractChild(e){return e.getChild(this.indexPath_)}isDefinedOn(e){return!e.getChild(this.indexPath_).isEmpty()}compare(e,n){const s=this.extractChild(e.node),i=this.extractChild(n.node),r=s.compareTo(i);return r===0?br(e.name,n.name):r}makePost(e,n){const s=Ct(e),i=Se.EMPTY_NODE.updateChild(this.indexPath_,s);return new _e(n,i)}maxPost(){const e=Se.EMPTY_NODE.updateChild(this.indexPath_,Go);return new _e(mi,e)}toString(){return N0(this.indexPath_,0).join("/")}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class NL extends mc{compare(e,n){const s=e.node.compareTo(n.node);return s===0?br(e.name,n.name):s}isDefinedOn(e){return!0}indexedValueChanged(e,n){return!e.equals(n)}minPost(){return _e.MIN}maxPost(){return _e.MAX}makePost(e,n){const s=Ct(e);return new _e(n,s)}toString(){return".value"}}const xL=new NL;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function DL(t){return{type:"value",snapshotNode:t}}function LL(t,e){return{type:"child_added",snapshotNode:e,childName:t}}function ML(t,e){return{type:"child_removed",snapshotNode:e,childName:t}}function o_(t,e,n){return{type:"child_changed",snapshotNode:e,childName:t,oldSnap:n}}function FL(t,e){return{type:"child_moved",snapshotNode:e,childName:t}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class of{constructor(){this.limitSet_=!1,this.startSet_=!1,this.startNameSet_=!1,this.startAfterSet_=!1,this.endSet_=!1,this.endNameSet_=!1,this.endBeforeSet_=!1,this.limit_=0,this.viewFrom_="",this.indexStartValue_=null,this.indexStartName_="",this.indexEndValue_=null,this.indexEndName_="",this.index_=It}hasStart(){return this.startSet_}isViewFromLeft(){return this.viewFrom_===""?this.startSet_:this.viewFrom_==="l"}getIndexStartValue(){return Y(this.startSet_,"Only valid if start has been set"),this.indexStartValue_}getIndexStartName(){return Y(this.startSet_,"Only valid if start has been set"),this.startNameSet_?this.indexStartName_:pr}hasEnd(){return this.endSet_}getIndexEndValue(){return Y(this.endSet_,"Only valid if end has been set"),this.indexEndValue_}getIndexEndName(){return Y(this.endSet_,"Only valid if end has been set"),this.endNameSet_?this.indexEndName_:mi}hasLimit(){return this.limitSet_}hasAnchoredLimit(){return this.limitSet_&&this.viewFrom_!==""}getLimit(){return Y(this.limitSet_,"Only valid if limit has been set"),this.limit_}getIndex(){return this.index_}loadsAllData(){return!(this.startSet_||this.endSet_||this.limitSet_)}isDefault(){return this.loadsAllData()&&this.index_===It}copy(){const e=new of;return e.limitSet_=this.limitSet_,e.limit_=this.limit_,e.startSet_=this.startSet_,e.startAfterSet_=this.startAfterSet_,e.indexStartValue_=this.indexStartValue_,e.startNameSet_=this.startNameSet_,e.indexStartName_=this.indexStartName_,e.endSet_=this.endSet_,e.endBeforeSet_=this.endBeforeSet_,e.indexEndValue_=this.indexEndValue_,e.endNameSet_=this.endNameSet_,e.indexEndName_=this.indexEndName_,e.index_=this.index_,e.viewFrom_=this.viewFrom_,e}}function a_(t){const e={};if(t.isDefault())return e;let n;if(t.index_===It?n="$priority":t.index_===xL?n="$value":t.index_===ir?n="$key":(Y(t.index_ instanceof OL,"Unrecognized index type!"),n=t.index_.toString()),e.orderBy=gt(n),t.startSet_){const s=t.startAfterSet_?"startAfter":"startAt";e[s]=gt(t.indexStartValue_),t.startNameSet_&&(e[s]+=","+gt(t.indexStartName_))}if(t.endSet_){const s=t.endBeforeSet_?"endBefore":"endAt";e[s]=gt(t.indexEndValue_),t.endNameSet_&&(e[s]+=","+gt(t.indexEndName_))}return t.limitSet_&&(t.isViewFromLeft()?e.limitToFirst=t.limit_:e.limitToLast=t.limit_),e}function l_(t){const e={};if(t.startSet_&&(e.sp=t.indexStartValue_,t.startNameSet_&&(e.sn=t.indexStartName_),e.sin=!t.startAfterSet_),t.endSet_&&(e.ep=t.indexEndValue_,t.endNameSet_&&(e.en=t.indexEndName_),e.ein=!t.endBeforeSet_),t.limitSet_){e.l=t.limit_;let n=t.viewFrom_;n===""&&(t.isViewFromLeft()?n="l":n="r"),e.vf=n}return t.index_!==It&&(e.i=t.index_.toString()),e}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Pl extends R0{constructor(e,n,s,i){super(),this.repoInfo_=e,this.onDataUpdate_=n,this.authTokenProvider_=s,this.appCheckTokenProvider_=i,this.log_=qo("p:rest:"),this.listens_={}}reportStats(e){throw new Error("Method not implemented.")}static getListenId_(e,n){return n!==void 0?"tag$"+n:(Y(e._queryParams.isDefault(),"should have a tag if it's not a default query."),e._path.toString())}listen(e,n,s,i){const r=e._path.toString();this.log_("Listen called for "+r+" "+e._queryIdentifier);const o=Pl.getListenId_(e,s),l={};this.listens_[o]=l;const c=a_(e._queryParams);this.restRequest_(r+".json",c,(u,h)=>{let f=h;if(u===404&&(f=null,u=null),u===null&&this.onDataUpdate_(r,f,!1,s),ur(this.listens_,o)===l){let g;u?u===401?g="permission_denied":g="rest_error:"+u:g="ok",i(g,null)}})}unlisten(e,n){const s=Pl.getListenId_(e,n);delete this.listens_[s]}get(e){const n=a_(e._queryParams),s=e._path.toString(),i=new vo;return this.restRequest_(s+".json",n,(r,o)=>{let l=o;r===404&&(l=null,r=null),r===null?(this.onDataUpdate_(s,l,!1,null),i.resolve(l)):i.reject(new Error(l))}),i.promise}refreshAuthToken(e){}restRequest_(e,n={},s){return n.format="export",Promise.all([this.authTokenProvider_.getToken(!1),this.appCheckTokenProvider_.getToken(!1)]).then(([i,r])=>{i&&i.accessToken&&(n.auth=i.accessToken),r&&r.token&&(n.ac=r.token);const o=(this.repoInfo_.secure?"https://":"http://")+this.repoInfo_.host+e+"?ns="+this.repoInfo_.namespace+vr(n);this.log_("Sending REST request for "+o);const l=new XMLHttpRequest;l.onreadystatechange=()=>{if(s&&l.readyState===4){this.log_("REST Response for "+o+" received. status:",l.status,"response:",l.responseText);let c=null;if(l.status>=200&&l.status<300){try{c=bo(l.responseText)}catch{Jt("Failed to parse JSON response for "+o+": "+l.responseText)}s(null,c)}else l.status!==401&&l.status!==404&&Jt("Got unsuccessful REST response for "+o+" Status: "+l.status),s(l.status);s=null}},l.open("GET",o,!0),l.send()})}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class UL{constructor(){this.rootNode_=Se.EMPTY_NODE}getNode(e){return this.rootNode_.getChild(e)}updateSnapshot(e,n){this.rootNode_=this.rootNode_.updateChild(e,n)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Ol(){return{value:null,children:new Map}}function B0(t,e,n){if(fe(e))t.value=n,t.children.clear();else if(t.value!==null)t.value=t.value.updateChild(e,n);else{const s=me(e);t.children.has(s)||t.children.set(s,Ol());const i=t.children.get(s);e=$e(e),B0(i,e,n)}}function Mh(t,e,n){t.value!==null?n(e,t.value):$L(t,(s,i)=>{const r=new Be(e.toString()+"/"+s);Mh(i,r,n)})}function $L(t,e){t.children.forEach((n,s)=>{e(s,n)})}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class HL{constructor(e){this.collection_=e,this.last_=null}get(){const e=this.collection_.get(),n=Object.assign({},e);return this.last_&&dn(this.last_,(s,i)=>{n[s]=n[s]-i}),this.last_=e,n}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const c_=10*1e3,BL=30*1e3,jL=5*60*1e3;class VL{constructor(e,n){this.server_=n,this.statsToReport_={},this.statsListener_=new HL(e);const s=c_+(BL-c_)*Math.random();lo(this.reportStats_.bind(this),Math.floor(s))}reportStats_(){const e=this.statsListener_.get(),n={};let s=!1;dn(e,(i,r)=>{r>0&&us(this.statsToReport_,i)&&(n[i]=r,s=!0)}),s&&this.server_.reportStats(n),lo(this.reportStats_.bind(this),Math.floor(Math.random()*2*jL))}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var On;(function(t){t[t.OVERWRITE=0]="OVERWRITE",t[t.MERGE=1]="MERGE",t[t.ACK_USER_WRITE=2]="ACK_USER_WRITE",t[t.LISTEN_COMPLETE=3]="LISTEN_COMPLETE"})(On||(On={}));function j0(){return{fromUser:!0,fromServer:!1,queryId:null,tagged:!1}}function V0(){return{fromUser:!1,fromServer:!0,queryId:null,tagged:!1}}function W0(t){return{fromUser:!1,fromServer:!0,queryId:t,tagged:!0}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Nl{constructor(e,n,s){this.path=e,this.affectedTree=n,this.revert=s,this.type=On.ACK_USER_WRITE,this.source=j0()}operationForChild(e){if(fe(this.path)){if(this.affectedTree.value!=null)return Y(this.affectedTree.children.isEmpty(),"affectedTree should not have overlapping affected paths."),this;{const n=this.affectedTree.subtree(new Be(e));return new Nl(Oe(),n,this.revert)}}else return Y(me(this.path)===e,"operationForChild called for unrelated child."),new Nl($e(this.path),this.affectedTree,this.revert)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class _i{constructor(e,n,s){this.source=e,this.path=n,this.snap=s,this.type=On.OVERWRITE}operationForChild(e){return fe(this.path)?new _i(this.source,Oe(),this.snap.getImmediateChild(e)):new _i(this.source,$e(this.path),this.snap)}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ro{constructor(e,n,s){this.source=e,this.path=n,this.children=s,this.type=On.MERGE}operationForChild(e){if(fe(this.path)){const n=this.children.subtree(new Be(e));return n.isEmpty()?null:n.value?new _i(this.source,Oe(),n.value):new Ro(this.source,Oe(),n)}else return Y(me(this.path)===e,"Can't get a merge for a child not on the path of the operation"),new Ro(this.source,$e(this.path),this.children)}toString(){return"Operation("+this.path+": "+this.source.toString()+" merge: "+this.children.toString()+")"}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class af{constructor(e,n,s){this.node_=e,this.fullyInitialized_=n,this.filtered_=s}isFullyInitialized(){return this.fullyInitialized_}isFiltered(){return this.filtered_}isCompleteForPath(e){if(fe(e))return this.isFullyInitialized()&&!this.filtered_;const n=me(e);return this.isCompleteForChild(n)}isCompleteForChild(e){return this.isFullyInitialized()&&!this.filtered_||this.node_.hasChild(e)}getNode(){return this.node_}}function WL(t,e,n,s){const i=[],r=[];return e.forEach(o=>{o.type==="child_changed"&&t.index_.indexedValueChanged(o.oldSnap,o.snapshotNode)&&r.push(FL(o.childName,o.snapshotNode))}),qr(t,i,"child_removed",e,s,n),qr(t,i,"child_added",e,s,n),qr(t,i,"child_moved",r,s,n),qr(t,i,"child_changed",e,s,n),qr(t,i,"value",e,s,n),i}function qr(t,e,n,s,i,r){const o=s.filter(l=>l.type===n);o.sort((l,c)=>qL(t,l,c)),o.forEach(l=>{const c=KL(t,l,r);i.forEach(u=>{u.respondsTo(l.type)&&e.push(u.createEvent(c,t.query_))})})}function KL(t,e,n){return e.type==="value"||e.type==="child_removed"||(e.prevName=n.getPredecessorChildName(e.childName,e.snapshotNode,t.index_)),e}function qL(t,e,n){if(e.childName==null||n.childName==null)throw wr("Should only compare child_ events.");const s=new _e(e.childName,e.snapshotNode),i=new _e(n.childName,n.snapshotNode);return t.index_.compare(s,i)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function K0(t,e){return{eventCache:t,serverCache:e}}function co(t,e,n,s){return K0(new af(e,n,s),t.serverCache)}function q0(t,e,n,s){return K0(t.eventCache,new af(e,n,s))}function Fh(t){return t.eventCache.isFullyInitialized()?t.eventCache.getNode():null}function yi(t){return t.serverCache.isFullyInitialized()?t.serverCache.getNode():null}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let Ru;const zL=()=>(Ru||(Ru=new Vt(ND)),Ru);class Ue{constructor(e,n=zL()){this.value=e,this.children=n}static fromObject(e){let n=new Ue(null);return dn(e,(s,i)=>{n=n.set(new Be(s),i)}),n}isEmpty(){return this.value===null&&this.children.isEmpty()}findRootMostMatchingPathAndValue(e,n){if(this.value!=null&&n(this.value))return{path:Oe(),value:this.value};if(fe(e))return null;{const s=me(e),i=this.children.get(s);if(i!==null){const r=i.findRootMostMatchingPathAndValue($e(e),n);return r!=null?{path:ct(new Be(s),r.path),value:r.value}:null}else return null}}findRootMostValueAndPath(e){return this.findRootMostMatchingPathAndValue(e,()=>!0)}subtree(e){if(fe(e))return this;{const n=me(e),s=this.children.get(n);return s!==null?s.subtree($e(e)):new Ue(null)}}set(e,n){if(fe(e))return new Ue(n,this.children);{const s=me(e),r=(this.children.get(s)||new Ue(null)).set($e(e),n),o=this.children.insert(s,r);return new Ue(this.value,o)}}remove(e){if(fe(e))return this.children.isEmpty()?new Ue(null):new Ue(null,this.children);{const n=me(e),s=this.children.get(n);if(s){const i=s.remove($e(e));let r;return i.isEmpty()?r=this.children.remove(n):r=this.children.insert(n,i),this.value===null&&r.isEmpty()?new Ue(null):new Ue(this.value,r)}else return this}}get(e){if(fe(e))return this.value;{const n=me(e),s=this.children.get(n);return s?s.get($e(e)):null}}setTree(e,n){if(fe(e))return n;{const s=me(e),r=(this.children.get(s)||new Ue(null)).setTree($e(e),n);let o;return r.isEmpty()?o=this.children.remove(s):o=this.children.insert(s,r),new Ue(this.value,o)}}fold(e){return this.fold_(Oe(),e)}fold_(e,n){const s={};return this.children.inorderTraversal((i,r)=>{s[i]=r.fold_(ct(e,i),n)}),n(e,this.value,s)}findOnPath(e,n){return this.findOnPath_(e,Oe(),n)}findOnPath_(e,n,s){const i=this.value?s(n,this.value):!1;if(i)return i;if(fe(e))return null;{const r=me(e),o=this.children.get(r);return o?o.findOnPath_($e(e),ct(n,r),s):null}}foreachOnPath(e,n){return this.foreachOnPath_(e,Oe(),n)}foreachOnPath_(e,n,s){if(fe(e))return this;{this.value&&s(n,this.value);const i=me(e),r=this.children.get(i);return r?r.foreachOnPath_($e(e),ct(n,i),s):new Ue(null)}}foreach(e){this.foreach_(Oe(),e)}foreach_(e,n){this.children.inorderTraversal((s,i)=>{i.foreach_(ct(e,s),n)}),this.value&&n(e,this.value)}foreachChild(e){this.children.inorderTraversal((n,s)=>{s.value&&e(n,s.value)})}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class bn{constructor(e){this.writeTree_=e}static empty(){return new bn(new Ue(null))}}function uo(t,e,n){if(fe(e))return new bn(new Ue(n));{const s=t.writeTree_.findRootMostValueAndPath(e);if(s!=null){const i=s.path;let r=s.value;const o=ln(i,e);return r=r.updateChild(o,n),new bn(t.writeTree_.set(i,r))}else{const i=new Ue(n),r=t.writeTree_.setTree(e,i);return new bn(r)}}}function u_(t,e,n){let s=t;return dn(n,(i,r)=>{s=uo(s,ct(e,i),r)}),s}function h_(t,e){if(fe(e))return bn.empty();{const n=t.writeTree_.setTree(e,new Ue(null));return new bn(n)}}function Uh(t,e){return Ci(t,e)!=null}function Ci(t,e){const n=t.writeTree_.findRootMostValueAndPath(e);return n!=null?t.writeTree_.get(n.path).getChild(ln(n.path,e)):null}function d_(t){const e=[],n=t.writeTree_.value;return n!=null?n.isLeafNode()||n.forEachChild(It,(s,i)=>{e.push(new _e(s,i))}):t.writeTree_.children.inorderTraversal((s,i)=>{i.value!=null&&e.push(new _e(s,i.value))}),e}function Ls(t,e){if(fe(e))return t;{const n=Ci(t,e);return n!=null?new bn(new Ue(n)):new bn(t.writeTree_.subtree(e))}}function $h(t){return t.writeTree_.isEmpty()}function gr(t,e){return z0(Oe(),t.writeTree_,e)}function z0(t,e,n){if(e.value!=null)return n.updateChild(t,e.value);{let s=null;return e.children.inorderTraversal((i,r)=>{i===".priority"?(Y(r.value!==null,"Priority writes must always be leaf nodes"),s=r.value):n=z0(ct(t,i),r,n)}),!n.getChild(t).isEmpty()&&s!==null&&(n=n.updateChild(ct(t,".priority"),s)),n}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function G0(t,e){return Z0(e,t)}function GL(t,e,n,s,i){Y(s>t.lastWriteId,"Stacking an older write on top of newer ones"),i===void 0&&(i=!0),t.allWrites.push({path:e,snap:n,writeId:s,visible:i}),i&&(t.visibleWrites=uo(t.visibleWrites,e,n)),t.lastWriteId=s}function YL(t,e){for(let n=0;n<t.allWrites.length;n++){const s=t.allWrites[n];if(s.writeId===e)return s}return null}function XL(t,e){const n=t.allWrites.findIndex(l=>l.writeId===e);Y(n>=0,"removeWrite called with nonexistent writeId.");const s=t.allWrites[n];t.allWrites.splice(n,1);let i=s.visible,r=!1,o=t.allWrites.length-1;for(;i&&o>=0;){const l=t.allWrites[o];l.visible&&(o>=n&&JL(l,s.path)?i=!1:yn(s.path,l.path)&&(r=!0)),o--}if(i){if(r)return QL(t),!0;if(s.snap)t.visibleWrites=h_(t.visibleWrites,s.path);else{const l=s.children;dn(l,c=>{t.visibleWrites=h_(t.visibleWrites,ct(s.path,c))})}return!0}else return!1}function JL(t,e){if(t.snap)return yn(t.path,e);for(const n in t.children)if(t.children.hasOwnProperty(n)&&yn(ct(t.path,n),e))return!0;return!1}function QL(t){t.visibleWrites=Y0(t.allWrites,ZL,Oe()),t.allWrites.length>0?t.lastWriteId=t.allWrites[t.allWrites.length-1].writeId:t.lastWriteId=-1}function ZL(t){return t.visible}function Y0(t,e,n){let s=bn.empty();for(let i=0;i<t.length;++i){const r=t[i];if(e(r)){const o=r.path;let l;if(r.snap)yn(n,o)?(l=ln(n,o),s=uo(s,l,r.snap)):yn(o,n)&&(l=ln(o,n),s=uo(s,Oe(),r.snap.getChild(l)));else if(r.children){if(yn(n,o))l=ln(n,o),s=u_(s,l,r.children);else if(yn(o,n))if(l=ln(o,n),fe(l))s=u_(s,Oe(),r.children);else{const c=ur(r.children,me(l));if(c){const u=c.getChild($e(l));s=uo(s,Oe(),u)}}}else throw wr("WriteRecord should have .snap or .children")}}return s}function X0(t,e,n,s,i){if(!s&&!i){const r=Ci(t.visibleWrites,e);if(r!=null)return r;{const o=Ls(t.visibleWrites,e);if($h(o))return n;if(n==null&&!Uh(o,Oe()))return null;{const l=n||Se.EMPTY_NODE;return gr(o,l)}}}else{const r=Ls(t.visibleWrites,e);if(!i&&$h(r))return n;if(!i&&n==null&&!Uh(r,Oe()))return null;{const o=function(u){return(u.visible||i)&&(!s||!~s.indexOf(u.writeId))&&(yn(u.path,e)||yn(e,u.path))},l=Y0(t.allWrites,o,e),c=n||Se.EMPTY_NODE;return gr(l,c)}}}function eM(t,e,n){let s=Se.EMPTY_NODE;const i=Ci(t.visibleWrites,e);if(i)return i.isLeafNode()||i.forEachChild(It,(r,o)=>{s=s.updateImmediateChild(r,o)}),s;if(n){const r=Ls(t.visibleWrites,e);return n.forEachChild(It,(o,l)=>{const c=gr(Ls(r,new Be(o)),l);s=s.updateImmediateChild(o,c)}),d_(r).forEach(o=>{s=s.updateImmediateChild(o.name,o.node)}),s}else{const r=Ls(t.visibleWrites,e);return d_(r).forEach(o=>{s=s.updateImmediateChild(o.name,o.node)}),s}}function tM(t,e,n,s,i){Y(s||i,"Either existingEventSnap or existingServerSnap must exist");const r=ct(e,n);if(Uh(t.visibleWrites,r))return null;{const o=Ls(t.visibleWrites,r);return $h(o)?i.getChild(n):gr(o,i.getChild(n))}}function nM(t,e,n,s){const i=ct(e,n),r=Ci(t.visibleWrites,i);if(r!=null)return r;if(s.isCompleteForChild(n)){const o=Ls(t.visibleWrites,i);return gr(o,s.getNode().getImmediateChild(n))}else return null}function sM(t,e){return Ci(t.visibleWrites,e)}function iM(t,e,n,s,i,r,o){let l;const c=Ls(t.visibleWrites,e),u=Ci(c,Oe());if(u!=null)l=u;else if(n!=null)l=gr(c,n);else return[];if(l=l.withIndex(o),!l.isEmpty()&&!l.isLeafNode()){const h=[],f=o.getCompare(),g=r?l.getReverseIteratorFrom(s,o):l.getIteratorFrom(s,o);let m=g.getNext();for(;m&&h.length<i;)f(m,s)!==0&&h.push(m),m=g.getNext();return h}else return[]}function rM(){return{visibleWrites:bn.empty(),allWrites:[],lastWriteId:-1}}function Hh(t,e,n,s){return X0(t.writeTree,t.treePath,e,n,s)}function J0(t,e){return eM(t.writeTree,t.treePath,e)}function f_(t,e,n,s){return tM(t.writeTree,t.treePath,e,n,s)}function xl(t,e){return sM(t.writeTree,ct(t.treePath,e))}function oM(t,e,n,s,i,r){return iM(t.writeTree,t.treePath,e,n,s,i,r)}function lf(t,e,n){return nM(t.writeTree,t.treePath,e,n)}function Q0(t,e){return Z0(ct(t.treePath,e),t.writeTree)}function Z0(t,e){return{treePath:t,writeTree:e}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class aM{constructor(){this.changeMap=new Map}trackChildChange(e){const n=e.type,s=e.childName;Y(n==="child_added"||n==="child_changed"||n==="child_removed","Only child changes supported for tracking"),Y(s!==".priority","Only non-priority child changes can be tracked.");const i=this.changeMap.get(s);if(i){const r=i.type;if(n==="child_added"&&r==="child_removed")this.changeMap.set(s,o_(s,e.snapshotNode,i.snapshotNode));else if(n==="child_removed"&&r==="child_added")this.changeMap.delete(s);else if(n==="child_removed"&&r==="child_changed")this.changeMap.set(s,ML(s,i.oldSnap));else if(n==="child_changed"&&r==="child_added")this.changeMap.set(s,LL(s,e.snapshotNode));else if(n==="child_changed"&&r==="child_changed")this.changeMap.set(s,o_(s,e.snapshotNode,i.oldSnap));else throw wr("Illegal combination of changes: "+e+" occurred after "+i)}else this.changeMap.set(s,e)}getChanges(){return Array.from(this.changeMap.values())}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class lM{getCompleteChild(e){return null}getChildAfterChild(e,n,s){return null}}const eE=new lM;class cf{constructor(e,n,s=null){this.writes_=e,this.viewCache_=n,this.optCompleteServerCache_=s}getCompleteChild(e){const n=this.viewCache_.eventCache;if(n.isCompleteForChild(e))return n.getNode().getImmediateChild(e);{const s=this.optCompleteServerCache_!=null?new af(this.optCompleteServerCache_,!0,!1):this.viewCache_.serverCache;return lf(this.writes_,e,s)}}getChildAfterChild(e,n,s){const i=this.optCompleteServerCache_!=null?this.optCompleteServerCache_:yi(this.viewCache_),r=oM(this.writes_,i,n,1,s,e);return r.length===0?null:r[0]}}function cM(t,e){Y(e.eventCache.getNode().isIndexed(t.filter.getIndex()),"Event snap not indexed"),Y(e.serverCache.getNode().isIndexed(t.filter.getIndex()),"Server snap not indexed")}function uM(t,e,n,s,i){const r=new aM;let o,l;if(n.type===On.OVERWRITE){const u=n;u.source.fromUser?o=Bh(t,e,u.path,u.snap,s,i,r):(Y(u.source.fromServer,"Unknown source."),l=u.source.tagged||e.serverCache.isFiltered()&&!fe(u.path),o=Dl(t,e,u.path,u.snap,s,i,l,r))}else if(n.type===On.MERGE){const u=n;u.source.fromUser?o=dM(t,e,u.path,u.children,s,i,r):(Y(u.source.fromServer,"Unknown source."),l=u.source.tagged||e.serverCache.isFiltered(),o=jh(t,e,u.path,u.children,s,i,l,r))}else if(n.type===On.ACK_USER_WRITE){const u=n;u.revert?o=gM(t,e,u.path,s,i,r):o=fM(t,e,u.path,u.affectedTree,s,i,r)}else if(n.type===On.LISTEN_COMPLETE)o=pM(t,e,n.path,s,r);else throw wr("Unknown operation type: "+n.type);const c=r.getChanges();return hM(e,o,c),{viewCache:o,changes:c}}function hM(t,e,n){const s=e.eventCache;if(s.isFullyInitialized()){const i=s.getNode().isLeafNode()||s.getNode().isEmpty(),r=Fh(t);(n.length>0||!t.eventCache.isFullyInitialized()||i&&!s.getNode().equals(r)||!s.getNode().getPriority().equals(r.getPriority()))&&n.push(DL(Fh(e)))}}function tE(t,e,n,s,i,r){const o=e.eventCache;if(xl(s,n)!=null)return e;{let l,c;if(fe(n))if(Y(e.serverCache.isFullyInitialized(),"If change path is empty, we must have complete server data"),e.serverCache.isFiltered()){const u=yi(e),h=u instanceof Se?u:Se.EMPTY_NODE,f=J0(s,h);l=t.filter.updateFullNode(e.eventCache.getNode(),f,r)}else{const u=Hh(s,yi(e));l=t.filter.updateFullNode(e.eventCache.getNode(),u,r)}else{const u=me(n);if(u===".priority"){Y(Fs(n)===1,"Can't have a priority with additional path components");const h=o.getNode();c=e.serverCache.getNode();const f=f_(s,n,h,c);f!=null?l=t.filter.updatePriority(h,f):l=o.getNode()}else{const h=$e(n);let f;if(o.isCompleteForChild(u)){c=e.serverCache.getNode();const g=f_(s,n,o.getNode(),c);g!=null?f=o.getNode().getImmediateChild(u).updateChild(h,g):f=o.getNode().getImmediateChild(u)}else f=lf(s,u,e.serverCache);f!=null?l=t.filter.updateChild(o.getNode(),u,f,h,i,r):l=o.getNode()}}return co(e,l,o.isFullyInitialized()||fe(n),t.filter.filtersNodes())}}function Dl(t,e,n,s,i,r,o,l){const c=e.serverCache;let u;const h=o?t.filter:t.filter.getIndexedFilter();if(fe(n))u=h.updateFullNode(c.getNode(),s,null);else if(h.filtersNodes()&&!c.isFiltered()){const m=c.getNode().updateChild(n,s);u=h.updateFullNode(c.getNode(),m,null)}else{const m=me(n);if(!c.isCompleteForPath(n)&&Fs(n)>1)return e;const I=$e(n),D=c.getNode().getImmediateChild(m).updateChild(I,s);m===".priority"?u=h.updatePriority(c.getNode(),D):u=h.updateChild(c.getNode(),m,D,I,eE,null)}const f=q0(e,u,c.isFullyInitialized()||fe(n),h.filtersNodes()),g=new cf(i,f,r);return tE(t,f,n,i,g,l)}function Bh(t,e,n,s,i,r,o){const l=e.eventCache;let c,u;const h=new cf(i,e,r);if(fe(n))u=t.filter.updateFullNode(e.eventCache.getNode(),s,o),c=co(e,u,!0,t.filter.filtersNodes());else{const f=me(n);if(f===".priority")u=t.filter.updatePriority(e.eventCache.getNode(),s),c=co(e,u,l.isFullyInitialized(),l.isFiltered());else{const g=$e(n),m=l.getNode().getImmediateChild(f);let I;if(fe(g))I=s;else{const P=h.getCompleteChild(f);P!=null?O0(g)===".priority"&&P.getChild(x0(g)).isEmpty()?I=P:I=P.updateChild(g,s):I=Se.EMPTY_NODE}if(m.equals(I))c=e;else{const P=t.filter.updateChild(l.getNode(),f,I,g,h,o);c=co(e,P,l.isFullyInitialized(),t.filter.filtersNodes())}}}return c}function p_(t,e){return t.eventCache.isCompleteForChild(e)}function dM(t,e,n,s,i,r,o){let l=e;return s.foreach((c,u)=>{const h=ct(n,c);p_(e,me(h))&&(l=Bh(t,l,h,u,i,r,o))}),s.foreach((c,u)=>{const h=ct(n,c);p_(e,me(h))||(l=Bh(t,l,h,u,i,r,o))}),l}function g_(t,e,n){return n.foreach((s,i)=>{e=e.updateChild(s,i)}),e}function jh(t,e,n,s,i,r,o,l){if(e.serverCache.getNode().isEmpty()&&!e.serverCache.isFullyInitialized())return e;let c=e,u;fe(n)?u=s:u=new Ue(null).setTree(n,s);const h=e.serverCache.getNode();return u.children.inorderTraversal((f,g)=>{if(h.hasChild(f)){const m=e.serverCache.getNode().getImmediateChild(f),I=g_(t,m,g);c=Dl(t,c,new Be(f),I,i,r,o,l)}}),u.children.inorderTraversal((f,g)=>{const m=!e.serverCache.isCompleteForChild(f)&&g.value===null;if(!h.hasChild(f)&&!m){const I=e.serverCache.getNode().getImmediateChild(f),P=g_(t,I,g);c=Dl(t,c,new Be(f),P,i,r,o,l)}}),c}function fM(t,e,n,s,i,r,o){if(xl(i,n)!=null)return e;const l=e.serverCache.isFiltered(),c=e.serverCache;if(s.value!=null){if(fe(n)&&c.isFullyInitialized()||c.isCompleteForPath(n))return Dl(t,e,n,c.getNode().getChild(n),i,r,l,o);if(fe(n)){let u=new Ue(null);return c.getNode().forEachChild(ir,(h,f)=>{u=u.set(new Be(h),f)}),jh(t,e,n,u,i,r,l,o)}else return e}else{let u=new Ue(null);return s.foreach((h,f)=>{const g=ct(n,h);c.isCompleteForPath(g)&&(u=u.set(h,c.getNode().getChild(g)))}),jh(t,e,n,u,i,r,l,o)}}function pM(t,e,n,s,i){const r=e.serverCache,o=q0(e,r.getNode(),r.isFullyInitialized()||fe(n),r.isFiltered());return tE(t,o,n,s,eE,i)}function gM(t,e,n,s,i,r){let o;if(xl(s,n)!=null)return e;{const l=new cf(s,e,i),c=e.eventCache.getNode();let u;if(fe(n)||me(n)===".priority"){let h;if(e.serverCache.isFullyInitialized())h=Hh(s,yi(e));else{const f=e.serverCache.getNode();Y(f instanceof Se,"serverChildren would be complete if leaf node"),h=J0(s,f)}h=h,u=t.filter.updateFullNode(c,h,r)}else{const h=me(n);let f=lf(s,h,e.serverCache);f==null&&e.serverCache.isCompleteForChild(h)&&(f=c.getImmediateChild(h)),f!=null?u=t.filter.updateChild(c,h,f,$e(n),l,r):e.eventCache.getNode().hasChild(h)?u=t.filter.updateChild(c,h,Se.EMPTY_NODE,$e(n),l,r):u=c,u.isEmpty()&&e.serverCache.isFullyInitialized()&&(o=Hh(s,yi(e)),o.isLeafNode()&&(u=t.filter.updateFullNode(u,o,r)))}return o=e.serverCache.isFullyInitialized()||xl(s,Oe())!=null,co(e,u,o,t.filter.filtersNodes())}}function mM(t,e){const n=yi(t.viewCache_);return n&&(t.query._queryParams.loadsAllData()||!fe(e)&&!n.getImmediateChild(me(e)).isEmpty())?n.getChild(e):null}function m_(t,e,n,s){e.type===On.MERGE&&e.source.queryId!==null&&(Y(yi(t.viewCache_),"We should always have a full cache before handling merges"),Y(Fh(t.viewCache_),"Missing event cache, even though we have a server cache"));const i=t.viewCache_,r=uM(t.processor_,i,e,n,s);return cM(t.processor_,r.viewCache),Y(r.viewCache.serverCache.isFullyInitialized()||!i.serverCache.isFullyInitialized(),"Once a server snap is complete, it should never go back"),t.viewCache_=r.viewCache,_M(t,r.changes,r.viewCache.eventCache.getNode())}function _M(t,e,n,s){const i=t.eventRegistrations_;return WL(t.eventGenerator_,e,n,i)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let __;function yM(t){Y(!__,"__referenceConstructor has already been defined"),__=t}function uf(t,e,n,s){const i=e.source.queryId;if(i!==null){const r=t.views.get(i);return Y(r!=null,"SyncTree gave us an op for an invalid query."),m_(r,e,n,s)}else{let r=[];for(const o of t.views.values())r=r.concat(m_(o,e,n,s));return r}}function hf(t,e){let n=null;for(const s of t.views.values())n=n||mM(s,e);return n}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */let y_;function wM(t){Y(!y_,"__referenceConstructor has already been defined"),y_=t}class w_{constructor(e){this.listenProvider_=e,this.syncPointTree_=new Ue(null),this.pendingWriteTree_=rM(),this.tagToQueryMap=new Map,this.queryToTagMap=new Map}}function vM(t,e,n,s,i){return GL(t.pendingWriteTree_,e,n,s,i),i?yc(t,new _i(j0(),e,n)):[]}function Ki(t,e,n=!1){const s=YL(t.pendingWriteTree_,e);if(XL(t.pendingWriteTree_,e)){let r=new Ue(null);return s.snap!=null?r=r.set(Oe(),!0):dn(s.children,o=>{r=r.set(new Be(o),!0)}),yc(t,new Nl(s.path,r,n))}else return[]}function _c(t,e,n){return yc(t,new _i(V0(),e,n))}function bM(t,e,n){const s=Ue.fromObject(n);return yc(t,new Ro(V0(),e,s))}function EM(t,e,n,s){const i=rE(t,s);if(i!=null){const r=oE(i),o=r.path,l=r.queryId,c=ln(o,e),u=new _i(W0(l),c,n);return aE(t,o,u)}else return[]}function TM(t,e,n,s){const i=rE(t,s);if(i){const r=oE(i),o=r.path,l=r.queryId,c=ln(o,e),u=Ue.fromObject(n),h=new Ro(W0(l),c,u);return aE(t,o,h)}else return[]}function nE(t,e,n){const i=t.pendingWriteTree_,r=t.syncPointTree_.findOnPath(e,(o,l)=>{const c=ln(o,e),u=hf(l,c);if(u)return u});return X0(i,e,r,n,!0)}function yc(t,e){return sE(e,t.syncPointTree_,null,G0(t.pendingWriteTree_,Oe()))}function sE(t,e,n,s){if(fe(t.path))return iE(t,e,n,s);{const i=e.get(Oe());n==null&&i!=null&&(n=hf(i,Oe()));let r=[];const o=me(t.path),l=t.operationForChild(o),c=e.children.get(o);if(c&&l){const u=n?n.getImmediateChild(o):null,h=Q0(s,o);r=r.concat(sE(l,c,u,h))}return i&&(r=r.concat(uf(i,t,s,n))),r}}function iE(t,e,n,s){const i=e.get(Oe());n==null&&i!=null&&(n=hf(i,Oe()));let r=[];return e.children.inorderTraversal((o,l)=>{const c=n?n.getImmediateChild(o):null,u=Q0(s,o),h=t.operationForChild(o);h&&(r=r.concat(iE(h,l,c,u)))}),i&&(r=r.concat(uf(i,t,s,n))),r}function rE(t,e){return t.tagToQueryMap.get(e)}function oE(t){const e=t.indexOf("$");return Y(e!==-1&&e<t.length-1,"Bad queryKey."),{queryId:t.substr(e+1),path:new Be(t.substr(0,e))}}function aE(t,e,n){const s=t.syncPointTree_.get(e);Y(s,"Missing sync point for query tag that we're tracking");const i=G0(t.pendingWriteTree_,e);return uf(s,n,i,null)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class df{constructor(e){this.node_=e}getImmediateChild(e){const n=this.node_.getImmediateChild(e);return new df(n)}node(){return this.node_}}class ff{constructor(e,n){this.syncTree_=e,this.path_=n}getImmediateChild(e){const n=ct(this.path_,e);return new ff(this.syncTree_,n)}node(){return nE(this.syncTree_,this.path_)}}const CM=function(t){return t=t||{},t.timestamp=t.timestamp||new Date().getTime(),t},v_=function(t,e,n){if(!t||typeof t!="object")return t;if(Y(".sv"in t,"Unexpected leaf node or priority contents"),typeof t[".sv"]=="string")return IM(t[".sv"],e,n);if(typeof t[".sv"]=="object")return SM(t[".sv"],e);Y(!1,"Unexpected server value: "+JSON.stringify(t,null,2))},IM=function(t,e,n){switch(t){case"timestamp":return n.timestamp;default:Y(!1,"Unexpected server value: "+t)}},SM=function(t,e,n){t.hasOwnProperty("increment")||Y(!1,"Unexpected server value: "+JSON.stringify(t,null,2));const s=t.increment;typeof s!="number"&&Y(!1,"Unexpected increment value: "+s);const i=e.node();if(Y(i!==null&&typeof i<"u","Expected ChildrenNode.EMPTY_NODE for nulls"),!i.isLeafNode())return s;const o=i.getValue();return typeof o!="number"?s:o+s},AM=function(t,e,n,s){return pf(e,new ff(n,t),s)},kM=function(t,e,n){return pf(t,new df(e),n)};function pf(t,e,n){const s=t.getPriority().val(),i=v_(s,e.getImmediateChild(".priority"),n);let r;if(t.isLeafNode()){const o=t,l=v_(o.getValue(),e,n);return l!==o.getValue()||i!==o.getPriority().val()?new at(l,Ct(i)):t}else{const o=t;return r=o,i!==o.getPriority().val()&&(r=r.updatePriority(new at(i))),o.forEachChild(It,(l,c)=>{const u=pf(c,e.getImmediateChild(l),n);u!==c&&(r=r.updateImmediateChild(l,u))}),r}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class gf{constructor(e="",n=null,s={children:{},childCount:0}){this.name=e,this.parent=n,this.node=s}}function mf(t,e){let n=e instanceof Be?e:new Be(e),s=t,i=me(n);for(;i!==null;){const r=ur(s.node.children,i)||{children:{},childCount:0};s=new gf(i,s,r),n=$e(n),i=me(n)}return s}function Er(t){return t.node.value}function lE(t,e){t.node.value=e,Vh(t)}function cE(t){return t.node.childCount>0}function RM(t){return Er(t)===void 0&&!cE(t)}function wc(t,e){dn(t.node.children,(n,s)=>{e(new gf(n,t,s))})}function uE(t,e,n,s){n&&!s&&e(t),wc(t,i=>{uE(i,e,!0,s)}),n&&s&&e(t)}function PM(t,e,n){let s=t.parent;for(;s!==null;){if(e(s))return!0;s=s.parent}return!1}function Yo(t){return new Be(t.parent===null?t.name:Yo(t.parent)+"/"+t.name)}function Vh(t){t.parent!==null&&OM(t.parent,t.name,t)}function OM(t,e,n){const s=RM(n),i=us(t.node.children,e);s&&i?(delete t.node.children[e],t.node.childCount--,Vh(t)):!s&&!i&&(t.node.children[e]=n.node,t.node.childCount++,Vh(t))}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const NM=/[\[\].#$\/\u0000-\u001F\u007F]/,xM=/[\[\].#$\u0000-\u001F\u007F]/,Pu=10*1024*1024,hE=function(t){return typeof t=="string"&&t.length!==0&&!NM.test(t)},DM=function(t){return typeof t=="string"&&t.length!==0&&!xM.test(t)},LM=function(t){return t&&(t=t.replace(/^\/*\.info(\/|$)/,"/")),DM(t)},dE=function(t,e,n){const s=n instanceof Be?new pL(n,t):n;if(e===void 0)throw new Error(t+"contains undefined "+ti(s));if(typeof e=="function")throw new Error(t+"contains a function "+ti(s)+" with contents = "+e.toString());if(h0(e))throw new Error(t+"contains "+e.toString()+" "+ti(s));if(typeof e=="string"&&e.length>Pu/3&&rc(e)>Pu)throw new Error(t+"contains a string greater than "+Pu+" utf8 bytes "+ti(s)+" ('"+e.substring(0,50)+"...')");if(e&&typeof e=="object"){let i=!1,r=!1;if(dn(e,(o,l)=>{if(o===".value")i=!0;else if(o!==".priority"&&o!==".sv"&&(r=!0,!hE(o)))throw new Error(t+" contains an invalid key ("+o+") "+ti(s)+`.  Keys must be non-empty strings and can't contain ".", "#", "$", "/", "[", or "]"`);gL(s,o),dE(t,l,s),mL(s)}),i&&r)throw new Error(t+' contains ".value" child '+ti(s)+" in addition to actual children.")}},MM=function(t,e){const n=e.path.toString();if(typeof e.repoInfo.host!="string"||e.repoInfo.host.length===0||!hE(e.repoInfo.namespace)&&e.repoInfo.host.split(":")[0]!=="localhost"||n.length!==0&&!LM(n))throw new Error(mk(t,"url")+`must be a valid firebase URL and the path can't contain ".", "#", "$", "[", or "]".`)};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class FM{constructor(){this.eventLists_=[],this.recursionDepth_=0}}function UM(t,e){let n=null;for(let s=0;s<e.length;s++){const i=e[s],r=i.getPath();n!==null&&!D0(r,n.path)&&(t.eventLists_.push(n),n=null),n===null&&(n={events:[],path:r}),n.events.push(i)}n&&t.eventLists_.push(n)}function Ii(t,e,n){UM(t,n),$M(t,s=>yn(s,e)||yn(e,s))}function $M(t,e){t.recursionDepth_++;let n=!0;for(let s=0;s<t.eventLists_.length;s++){const i=t.eventLists_[s];if(i){const r=i.path;e(r)?(HM(t.eventLists_[s]),t.eventLists_[s]=null):n=!1}}n&&(t.eventLists_=[]),t.recursionDepth_--}function HM(t){for(let e=0;e<t.events.length;e++){const n=t.events[e];if(n!==null){t.events[e]=null;const s=n.getEventRunner();ao&&Tt("event: "+n.toString()),zo(s)}}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const BM="repo_interrupt",jM=25;class VM{constructor(e,n,s,i){this.repoInfo_=e,this.forceRestClient_=n,this.authTokenProvider_=s,this.appCheckProvider_=i,this.dataUpdateCount=0,this.statsListener_=null,this.eventQueue_=new FM,this.nextWriteId_=1,this.interceptServerDataCallback_=null,this.onDisconnect_=Ol(),this.transactionQueueTree_=new gf,this.persistentConnection_=null,this.key=this.repoInfo_.toURLString()}toString(){return(this.repoInfo_.secure?"https://":"http://")+this.repoInfo_.host}}function WM(t,e,n){if(t.stats_=tf(t.repoInfo_),t.forceRestClient_||UD())t.server_=new Pl(t.repoInfo_,(s,i,r,o)=>{b_(t,s,i,r,o)},t.authTokenProvider_,t.appCheckProvider_),setTimeout(()=>E_(t,!0),0);else{if(typeof n<"u"&&n!==null){if(typeof n!="object")throw new Error("Only objects are supported for option databaseAuthVariableOverride");try{gt(n)}catch(s){throw new Error("Invalid authOverride provided: "+s)}}t.persistentConnection_=new ts(t.repoInfo_,e,(s,i,r,o)=>{b_(t,s,i,r,o)},s=>{E_(t,s)},s=>{qM(t,s)},t.authTokenProvider_,t.appCheckProvider_,n),t.server_=t.persistentConnection_}t.authTokenProvider_.addTokenChangeListener(s=>{t.server_.refreshAuthToken(s)}),t.appCheckProvider_.addTokenChangeListener(s=>{t.server_.refreshAppCheckToken(s.token)}),t.statsReporter_=WD(t.repoInfo_,()=>new VL(t.stats_,t.server_)),t.infoData_=new UL,t.infoSyncTree_=new w_({startListening:(s,i,r,o)=>{let l=[];const c=t.infoData_.getNode(s._path);return c.isEmpty()||(l=_c(t.infoSyncTree_,s._path,c),setTimeout(()=>{o("ok")},0)),l},stopListening:()=>{}}),_f(t,"connected",!1),t.serverSyncTree_=new w_({startListening:(s,i,r,o)=>(t.server_.listen(s,r,i,(l,c)=>{const u=o(l,c);Ii(t.eventQueue_,s._path,u)}),[]),stopListening:(s,i)=>{t.server_.unlisten(s,i)}})}function KM(t){const n=t.infoData_.getNode(new Be(".info/serverTimeOffset")).val()||0;return new Date().getTime()+n}function fE(t){return CM({timestamp:KM(t)})}function b_(t,e,n,s,i){t.dataUpdateCount++;const r=new Be(e);n=t.interceptServerDataCallback_?t.interceptServerDataCallback_(e,n):n;let o=[];if(i)if(s){const c=hl(n,u=>Ct(u));o=TM(t.serverSyncTree_,r,c,i)}else{const c=Ct(n);o=EM(t.serverSyncTree_,r,c,i)}else if(s){const c=hl(n,u=>Ct(u));o=bM(t.serverSyncTree_,r,c)}else{const c=Ct(n);o=_c(t.serverSyncTree_,r,c)}let l=r;o.length>0&&(l=wf(t,r)),Ii(t.eventQueue_,l,o)}function E_(t,e){_f(t,"connected",e),e===!1&&GM(t)}function qM(t,e){dn(e,(n,s)=>{_f(t,n,s)})}function _f(t,e,n){const s=new Be("/.info/"+e),i=Ct(n);t.infoData_.updateSnapshot(s,i);const r=_c(t.infoSyncTree_,s,i);Ii(t.eventQueue_,s,r)}function zM(t){return t.nextWriteId_++}function GM(t){pE(t,"onDisconnectEvents");const e=fE(t),n=Ol();Mh(t.onDisconnect_,Oe(),(i,r)=>{const o=AM(i,r,t.serverSyncTree_,e);B0(n,i,o)});let s=[];Mh(n,Oe(),(i,r)=>{s=s.concat(_c(t.serverSyncTree_,i,r));const o=QM(t,i);wf(t,o)}),t.onDisconnect_=Ol(),Ii(t.eventQueue_,Oe(),s)}function YM(t){t.persistentConnection_&&t.persistentConnection_.interrupt(BM)}function pE(t,...e){let n="";t.persistentConnection_&&(n=t.persistentConnection_.id+":"),Tt(n,...e)}function gE(t,e,n){return nE(t.serverSyncTree_,e,n)||Se.EMPTY_NODE}function yf(t,e=t.transactionQueueTree_){if(e||vc(t,e),Er(e)){const n=_E(t,e);Y(n.length>0,"Sending zero length transaction queue"),n.every(i=>i.status===0)&&XM(t,Yo(e),n)}else cE(e)&&wc(e,n=>{yf(t,n)})}function XM(t,e,n){const s=n.map(u=>u.currentWriteId),i=gE(t,e,s);let r=i;const o=i.hash();for(let u=0;u<n.length;u++){const h=n[u];Y(h.status===0,"tryToSendTransactionQueue_: items in queue should all be run."),h.status=1,h.retryCount++;const f=ln(e,h.path);r=r.updateChild(f,h.currentOutputSnapshotRaw)}const l=r.val(!0),c=e;t.server_.put(c.toString(),l,u=>{pE(t,"transaction put response",{path:c.toString(),status:u});let h=[];if(u==="ok"){const f=[];for(let g=0;g<n.length;g++)n[g].status=2,h=h.concat(Ki(t.serverSyncTree_,n[g].currentWriteId)),n[g].onComplete&&f.push(()=>n[g].onComplete(null,!0,n[g].currentOutputSnapshotResolved)),n[g].unwatcher();vc(t,mf(t.transactionQueueTree_,e)),yf(t,t.transactionQueueTree_),Ii(t.eventQueue_,e,h);for(let g=0;g<f.length;g++)zo(f[g])}else{if(u==="datastale")for(let f=0;f<n.length;f++)n[f].status===3?n[f].status=4:n[f].status=0;else{Jt("transaction at "+c.toString()+" failed: "+u);for(let f=0;f<n.length;f++)n[f].status=4,n[f].abortReason=u}wf(t,e)}},o)}function wf(t,e){const n=mE(t,e),s=Yo(n),i=_E(t,n);return JM(t,i,s),s}function JM(t,e,n){if(e.length===0)return;const s=[];let i=[];const o=e.filter(l=>l.status===0).map(l=>l.currentWriteId);for(let l=0;l<e.length;l++){const c=e[l],u=ln(n,c.path);let h=!1,f;if(Y(u!==null,"rerunTransactionsUnderNode_: relativePath should not be null."),c.status===4)h=!0,f=c.abortReason,i=i.concat(Ki(t.serverSyncTree_,c.currentWriteId,!0));else if(c.status===0)if(c.retryCount>=jM)h=!0,f="maxretry",i=i.concat(Ki(t.serverSyncTree_,c.currentWriteId,!0));else{const g=gE(t,c.path,o);c.currentInputSnapshot=g;const m=e[l].update(g.val());if(m!==void 0){dE("transaction failed: Data returned ",m,c.path);let I=Ct(m);typeof m=="object"&&m!=null&&us(m,".priority")||(I=I.updatePriority(g.getPriority()));const D=c.currentWriteId,M=fE(t),x=kM(I,g,M);c.currentOutputSnapshotRaw=I,c.currentOutputSnapshotResolved=x,c.currentWriteId=zM(t),o.splice(o.indexOf(D),1),i=i.concat(vM(t.serverSyncTree_,c.path,x,c.currentWriteId,c.applyLocally)),i=i.concat(Ki(t.serverSyncTree_,D,!0))}else h=!0,f="nodata",i=i.concat(Ki(t.serverSyncTree_,c.currentWriteId,!0))}Ii(t.eventQueue_,n,i),i=[],h&&(e[l].status=2,function(g){setTimeout(g,Math.floor(0))}(e[l].unwatcher),e[l].onComplete&&(f==="nodata"?s.push(()=>e[l].onComplete(null,!1,e[l].currentInputSnapshot)):s.push(()=>e[l].onComplete(new Error(f),!1,null))))}vc(t,t.transactionQueueTree_);for(let l=0;l<s.length;l++)zo(s[l]);yf(t,t.transactionQueueTree_)}function mE(t,e){let n,s=t.transactionQueueTree_;for(n=me(e);n!==null&&Er(s)===void 0;)s=mf(s,n),e=$e(e),n=me(e);return s}function _E(t,e){const n=[];return yE(t,e,n),n.sort((s,i)=>s.order-i.order),n}function yE(t,e,n){const s=Er(e);if(s)for(let i=0;i<s.length;i++)n.push(s[i]);wc(e,i=>{yE(t,i,n)})}function vc(t,e){const n=Er(e);if(n){let s=0;for(let i=0;i<n.length;i++)n[i].status!==2&&(n[s]=n[i],s++);n.length=s,lE(e,n.length>0?n:void 0)}wc(e,s=>{vc(t,s)})}function QM(t,e){const n=Yo(mE(t,e)),s=mf(t.transactionQueueTree_,e);return PM(s,i=>{Ou(t,i)}),Ou(t,s),uE(s,i=>{Ou(t,i)}),n}function Ou(t,e){const n=Er(e);if(n){const s=[];let i=[],r=-1;for(let o=0;o<n.length;o++)n[o].status===3||(n[o].status===1?(Y(r===o-1,"All SENT items should be at beginning of queue."),r=o,n[o].status=3,n[o].abortReason="set"):(Y(n[o].status===0,"Unexpected transaction status in abort"),n[o].unwatcher(),i=i.concat(Ki(t.serverSyncTree_,n[o].currentWriteId,!0)),n[o].onComplete&&s.push(n[o].onComplete.bind(null,new Error("set"),!1,null))));r===-1?lE(e,void 0):n.length=r+1,Ii(t.eventQueue_,Yo(e),i);for(let o=0;o<s.length;o++)zo(s[o])}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function ZM(t){let e="";const n=t.split("/");for(let s=0;s<n.length;s++)if(n[s].length>0){let i=n[s];try{i=decodeURIComponent(i.replace(/\+/g," "))}catch{}e+="/"+i}return e}function eF(t){const e={};t.charAt(0)==="?"&&(t=t.substring(1));for(const n of t.split("&")){if(n.length===0)continue;const s=n.split("=");s.length===2?e[decodeURIComponent(s[0])]=decodeURIComponent(s[1]):Jt(`Invalid query segment '${n}' in query '${t}'`)}return e}const T_=function(t,e){const n=tF(t),s=n.namespace;n.domain==="firebase.com"&&gi(n.host+" is no longer supported. Please use <YOUR FIREBASE>.firebaseio.com instead"),(!s||s==="undefined")&&n.domain!=="localhost"&&gi("Cannot parse Firebase url. Please use https://<YOUR FIREBASE>.firebaseio.com"),n.secure||PD();const i=n.scheme==="ws"||n.scheme==="wss";return{repoInfo:new BD(n.host,n.secure,s,i,e,"",s!==n.subdomain),path:new Be(n.pathString)}},tF=function(t){let e="",n="",s="",i="",r="",o=!0,l="https",c=443;if(typeof t=="string"){let u=t.indexOf("//");u>=0&&(l=t.substring(0,u-1),t=t.substring(u+2));let h=t.indexOf("/");h===-1&&(h=t.length);let f=t.indexOf("?");f===-1&&(f=t.length),e=t.substring(0,Math.min(h,f)),h<f&&(i=ZM(t.substring(h,f)));const g=eF(t.substring(Math.min(t.length,f)));u=e.indexOf(":"),u>=0?(o=l==="https"||l==="wss",c=parseInt(e.substring(u+1),10)):u=e.length;const m=e.slice(0,u);if(m.toLowerCase()==="localhost")n="localhost";else if(m.split(".").length<=2)n=m;else{const I=e.indexOf(".");s=e.substring(0,I).toLowerCase(),n=e.substring(I+1),r=s}"ns"in g&&(r=g.ns)}return{host:e,port:c,domain:n,subdomain:s,secure:o,scheme:l,pathString:i,namespace:r}};/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class vf{constructor(e,n,s,i){this._repo=e,this._path=n,this._queryParams=s,this._orderByCalled=i}get key(){return fe(this._path)?null:O0(this._path)}get ref(){return new Tr(this._repo,this._path)}get _queryIdentifier(){const e=l_(this._queryParams),n=Zd(e);return n==="{}"?"default":n}get _queryObject(){return l_(this._queryParams)}isEqual(e){if(e=Un(e),!(e instanceof vf))return!1;const n=this._repo===e._repo,s=D0(this._path,e._path),i=this._queryIdentifier===e._queryIdentifier;return n&&s&&i}toJSON(){return this.toString()}toString(){return this._repo.toString()+fL(this._path)}}class Tr extends vf{constructor(e,n){super(e,n,new of,!1)}get parent(){const e=x0(this._path);return e===null?null:new Tr(this._repo,e)}get root(){let e=this;for(;e.parent!==null;)e=e.parent;return e}}yM(Tr);wM(Tr);/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const nF="FIREBASE_DATABASE_EMULATOR_HOST",Wh={};let sF=!1;function iF(t,e,n,s,i){let r=s||t.options.databaseURL;r===void 0&&(t.options.projectId||gi("Can't determine Firebase Database URL. Be sure to include  a Project ID when calling firebase.initializeApp()."),Tt("Using default host for project ",t.options.projectId),r=`${t.options.projectId}-default-rtdb.firebaseio.com`);let o=T_(r,i),l=o.repoInfo,c;typeof process<"u"&&Vm&&(c=Vm[nF]),c?(r=`http://${c}?ns=${l.namespace}`,o=T_(r,i),l=o.repoInfo):o.repoInfo.secure;const u=new HD(t.name,t.options,e);MM("Invalid Firebase Database URL",o),fe(o.path)||gi("Database URL must point to the root of a Firebase Database (not including a child path).");const h=oF(l,t,u,new $D(t.name,n));return new aF(h,t)}function rF(t,e){const n=Wh[e];(!n||n[t.key]!==t)&&gi(`Database ${e}(${t.repoInfo_}) has already been deleted.`),YM(t),delete n[t.key]}function oF(t,e,n,s){let i=Wh[e.name];i||(i={},Wh[e.name]=i);let r=i[t.toURLString()];return r&&gi("Database initialized multiple times. Please make sure the format of the database URL matches with each database() call."),r=new VM(t,sF,n,s),i[t.toURLString()]=r,r}class aF{constructor(e,n){this._repoInternal=e,this.app=n,this.type="database",this._instanceStarted=!1}get _repo(){return this._instanceStarted||(WM(this._repoInternal,this.app.options.appId,this.app.options.databaseAuthVariableOverride),this._instanceStarted=!0),this._repoInternal}get _root(){return this._rootInternal||(this._rootInternal=new Tr(this._repo,Oe())),this._rootInternal}_delete(){return this._rootInternal!==null&&(rF(this._repo,this.app.name),this._repoInternal=null,this._rootInternal=null),Promise.resolve()}_checkNotDeleted(e){this._rootInternal===null&&gi("Cannot call "+e+" on a deleted database.")}}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function lF(t){CD(js),Kt(new Mt("database",(e,{instanceIdentifier:n})=>{const s=e.getProvider("app").getImmediate(),i=e.getProvider("auth-internal"),r=e.getProvider("app-check-internal");return iF(s,i,r,n)},"PUBLIC").setMultipleInstances(!0)),mt(Wm,Km,t),mt(Wm,Km,"esm2017")}ts.prototype.simpleListen=function(t,e){this.sendRequest("q",{p:t},e)};ts.prototype.echo=function(t,e){this.sendRequest("echo",{d:t},e)};lF();/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const wE="firebasestorage.googleapis.com",cF="storageBucket",uF=2*60*1e3,hF=10*60*1e3;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class $n extends Sn{constructor(e,n,s=0){super(Nu(e),`Firebase Storage: ${n} (${Nu(e)})`),this.status_=s,this.customData={serverResponse:null},this._baseMessage=this.message,Object.setPrototypeOf(this,$n.prototype)}get status(){return this.status_}set status(e){this.status_=e}_codeEquals(e){return Nu(e)===this.code}get serverResponse(){return this.customData.serverResponse}set serverResponse(e){this.customData.serverResponse=e,this.customData.serverResponse?this.message=`${this._baseMessage}
${this.customData.serverResponse}`:this.message=this._baseMessage}}var Ln;(function(t){t.UNKNOWN="unknown",t.OBJECT_NOT_FOUND="object-not-found",t.BUCKET_NOT_FOUND="bucket-not-found",t.PROJECT_NOT_FOUND="project-not-found",t.QUOTA_EXCEEDED="quota-exceeded",t.UNAUTHENTICATED="unauthenticated",t.UNAUTHORIZED="unauthorized",t.UNAUTHORIZED_APP="unauthorized-app",t.RETRY_LIMIT_EXCEEDED="retry-limit-exceeded",t.INVALID_CHECKSUM="invalid-checksum",t.CANCELED="canceled",t.INVALID_EVENT_NAME="invalid-event-name",t.INVALID_URL="invalid-url",t.INVALID_DEFAULT_BUCKET="invalid-default-bucket",t.NO_DEFAULT_BUCKET="no-default-bucket",t.CANNOT_SLICE_BLOB="cannot-slice-blob",t.SERVER_FILE_WRONG_SIZE="server-file-wrong-size",t.NO_DOWNLOAD_URL="no-download-url",t.INVALID_ARGUMENT="invalid-argument",t.INVALID_ARGUMENT_COUNT="invalid-argument-count",t.APP_DELETED="app-deleted",t.INVALID_ROOT_OPERATION="invalid-root-operation",t.INVALID_FORMAT="invalid-format",t.INTERNAL_ERROR="internal-error",t.UNSUPPORTED_ENVIRONMENT="unsupported-environment"})(Ln||(Ln={}));function Nu(t){return"storage/"+t}function dF(){const t="An unknown error occurred, please check the error payload for server response.";return new $n(Ln.UNKNOWN,t)}function fF(){return new $n(Ln.RETRY_LIMIT_EXCEEDED,"Max retry time for operation exceeded, please try again.")}function pF(){return new $n(Ln.CANCELED,"User canceled the upload/download.")}function gF(t){return new $n(Ln.INVALID_URL,"Invalid URL '"+t+"'.")}function mF(t){return new $n(Ln.INVALID_DEFAULT_BUCKET,"Invalid default bucket '"+t+"'.")}function C_(t){return new $n(Ln.INVALID_ARGUMENT,t)}function vE(){return new $n(Ln.APP_DELETED,"The Firebase app was deleted.")}function _F(t){return new $n(Ln.INVALID_ROOT_OPERATION,"The operation '"+t+"' cannot be performed on a root reference, create a non-root reference using child, such as .child('file.png').")}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class wn{constructor(e,n){this.bucket=e,this.path_=n}get path(){return this.path_}get isRoot(){return this.path.length===0}fullServerUrl(){const e=encodeURIComponent;return"/b/"+e(this.bucket)+"/o/"+e(this.path)}bucketOnlyServerUrl(){return"/b/"+encodeURIComponent(this.bucket)+"/o"}static makeFromBucketSpec(e,n){let s;try{s=wn.makeFromUrl(e,n)}catch{return new wn(e,"")}if(s.path==="")return s;throw mF(e)}static makeFromUrl(e,n){let s=null;const i="([A-Za-z0-9.\\-_]+)";function r(R){R.path.charAt(R.path.length-1)==="/"&&(R.path_=R.path_.slice(0,-1))}const o="(/(.*))?$",l=new RegExp("^gs://"+i+o,"i"),c={bucket:1,path:3};function u(R){R.path_=decodeURIComponent(R.path)}const h="v[A-Za-z0-9_]+",f=n.replace(/[.]/g,"\\."),g="(/([^?#]*).*)?$",m=new RegExp(`^https?://${f}/${h}/b/${i}/o${g}`,"i"),I={bucket:1,path:3},P=n===wE?"(?:storage.googleapis.com|storage.cloud.google.com)":n,D="([^?#]*)",M=new RegExp(`^https?://${P}/${i}/${D}`,"i"),b=[{regex:l,indices:c,postModify:r},{regex:m,indices:I,postModify:u},{regex:M,indices:{bucket:1,path:2},postModify:u}];for(let R=0;R<b.length;R++){const N=b[R],F=N.regex.exec(e);if(F){const T=F[N.indices.bucket];let w=F[N.indices.path];w||(w=""),s=new wn(T,w),N.postModify(s);break}}if(s==null)throw gF(e);return s}}class yF{constructor(e){this.promise_=Promise.reject(e)}getPromise(){return this.promise_}cancel(e=!1){}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function wF(t,e,n){let s=1,i=null,r=null,o=!1,l=0;function c(){return l===2}let u=!1;function h(...D){u||(u=!0,e.apply(null,D))}function f(D){i=setTimeout(()=>{i=null,t(m,c())},D)}function g(){r&&clearTimeout(r)}function m(D,...M){if(u){g();return}if(D){g(),h.call(null,D,...M);return}if(c()||o){g(),h.call(null,D,...M);return}s<64&&(s*=2);let b;l===1?(l=2,b=0):b=(s+Math.random())*1e3,f(b)}let I=!1;function P(D){I||(I=!0,g(),!u&&(i!==null?(D||(l=2),clearTimeout(i),f(0)):D||(l=1)))}return f(0),r=setTimeout(()=>{o=!0,P(!0)},n),P}function vF(t){t(!1)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function bF(t){return t!==void 0}function I_(t,e,n,s){if(s<e)throw C_(`Invalid value for '${t}'. Expected ${e} or greater.`);if(s>n)throw C_(`Invalid value for '${t}'. Expected ${n} or less.`)}function EF(t){const e=encodeURIComponent;let n="?";for(const s in t)if(t.hasOwnProperty(s)){const i=e(s)+"="+e(t[s]);n=n+i+"&"}return n=n.slice(0,-1),n}var Ll;(function(t){t[t.NO_ERROR=0]="NO_ERROR",t[t.NETWORK_ERROR=1]="NETWORK_ERROR",t[t.ABORT=2]="ABORT"})(Ll||(Ll={}));/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function TF(t,e){const n=t>=500&&t<600,i=[408,429].indexOf(t)!==-1,r=e.indexOf(t)!==-1;return n||i||r}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class CF{constructor(e,n,s,i,r,o,l,c,u,h,f,g=!0){this.url_=e,this.method_=n,this.headers_=s,this.body_=i,this.successCodes_=r,this.additionalRetryCodes_=o,this.callback_=l,this.errorCallback_=c,this.timeout_=u,this.progressCallback_=h,this.connectionFactory_=f,this.retry=g,this.pendingConnection_=null,this.backoffId_=null,this.canceled_=!1,this.appDelete_=!1,this.promise_=new Promise((m,I)=>{this.resolve_=m,this.reject_=I,this.start_()})}start_(){const e=(s,i)=>{if(i){s(!1,new Ma(!1,null,!0));return}const r=this.connectionFactory_();this.pendingConnection_=r;const o=l=>{const c=l.loaded,u=l.lengthComputable?l.total:-1;this.progressCallback_!==null&&this.progressCallback_(c,u)};this.progressCallback_!==null&&r.addUploadProgressListener(o),r.send(this.url_,this.method_,this.body_,this.headers_).then(()=>{this.progressCallback_!==null&&r.removeUploadProgressListener(o),this.pendingConnection_=null;const l=r.getErrorCode()===Ll.NO_ERROR,c=r.getStatus();if(!l||TF(c,this.additionalRetryCodes_)&&this.retry){const h=r.getErrorCode()===Ll.ABORT;s(!1,new Ma(!1,null,h));return}const u=this.successCodes_.indexOf(c)!==-1;s(!0,new Ma(u,r))})},n=(s,i)=>{const r=this.resolve_,o=this.reject_,l=i.connection;if(i.wasSuccessCode)try{const c=this.callback_(l,l.getResponse());bF(c)?r(c):r()}catch(c){o(c)}else if(l!==null){const c=dF();c.serverResponse=l.getErrorText(),this.errorCallback_?o(this.errorCallback_(l,c)):o(c)}else if(i.canceled){const c=this.appDelete_?vE():pF();o(c)}else{const c=fF();o(c)}};this.canceled_?n(!1,new Ma(!1,null,!0)):this.backoffId_=wF(e,n,this.timeout_)}getPromise(){return this.promise_}cancel(e){this.canceled_=!0,this.appDelete_=e||!1,this.backoffId_!==null&&vF(this.backoffId_),this.pendingConnection_!==null&&this.pendingConnection_.abort()}}class Ma{constructor(e,n,s){this.wasSuccessCode=e,this.connection=n,this.canceled=!!s}}function IF(t,e){e!==null&&e.length>0&&(t.Authorization="Firebase "+e)}function SF(t,e){t["X-Firebase-Storage-Version"]="webjs/"+(e??"AppManager")}function AF(t,e){e&&(t["X-Firebase-GMPID"]=e)}function kF(t,e){e!==null&&(t["X-Firebase-AppCheck"]=e)}function RF(t,e,n,s,i,r,o=!0){const l=EF(t.urlParams),c=t.url+l,u=Object.assign({},t.headers);return AF(u,e),IF(u,n),SF(u,r),kF(u,s),new CF(c,t.method,u,t.body,t.successCodes,t.additionalRetryCodes,t.handler,t.errorHandler,t.timeout,t.progressCallback,i,o)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function PF(t){if(t.length===0)return null;const e=t.lastIndexOf("/");return e===-1?"":t.slice(0,e)}function OF(t){const e=t.lastIndexOf("/",t.length-2);return e===-1?t:t.slice(e+1)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ml{constructor(e,n){this._service=e,n instanceof wn?this._location=n:this._location=wn.makeFromUrl(n,e.host)}toString(){return"gs://"+this._location.bucket+"/"+this._location.path}_newRef(e,n){return new Ml(e,n)}get root(){const e=new wn(this._location.bucket,"");return this._newRef(this._service,e)}get bucket(){return this._location.bucket}get fullPath(){return this._location.path}get name(){return OF(this._location.path)}get storage(){return this._service}get parent(){const e=PF(this._location.path);if(e===null)return null;const n=new wn(this._location.bucket,e);return new Ml(this._service,n)}_throwIfRoot(e){if(this._location.path==="")throw _F(e)}}function S_(t,e){const n=e==null?void 0:e[cF];return n==null?null:wn.makeFromBucketSpec(n,t)}class NF{constructor(e,n,s,i,r){this.app=e,this._authProvider=n,this._appCheckProvider=s,this._url=i,this._firebaseVersion=r,this._bucket=null,this._host=wE,this._protocol="https",this._appId=null,this._deleted=!1,this._maxOperationRetryTime=uF,this._maxUploadRetryTime=hF,this._requests=new Set,i!=null?this._bucket=wn.makeFromBucketSpec(i,this._host):this._bucket=S_(this._host,this.app.options)}get host(){return this._host}set host(e){this._host=e,this._url!=null?this._bucket=wn.makeFromBucketSpec(this._url,e):this._bucket=S_(e,this.app.options)}get maxUploadRetryTime(){return this._maxUploadRetryTime}set maxUploadRetryTime(e){I_("time",0,Number.POSITIVE_INFINITY,e),this._maxUploadRetryTime=e}get maxOperationRetryTime(){return this._maxOperationRetryTime}set maxOperationRetryTime(e){I_("time",0,Number.POSITIVE_INFINITY,e),this._maxOperationRetryTime=e}async _getAuthToken(){if(this._overrideAuthToken)return this._overrideAuthToken;const e=this._authProvider.getImmediate({optional:!0});if(e){const n=await e.getToken();if(n!==null)return n.accessToken}return null}async _getAppCheckToken(){const e=this._appCheckProvider.getImmediate({optional:!0});return e?(await e.getToken()).token:null}_delete(){return this._deleted||(this._deleted=!0,this._requests.forEach(e=>e.cancel()),this._requests.clear()),Promise.resolve()}_makeStorageReference(e){return new Ml(this,e)}_makeRequest(e,n,s,i,r=!0){if(this._deleted)return new yF(vE());{const o=RF(e,this._appId,s,i,n,this._firebaseVersion,r);return this._requests.add(o),o.getPromise().then(()=>this._requests.delete(o),()=>this._requests.delete(o)),o}}async makeRequestWithTokens(e,n){const[s,i]=await Promise.all([this._getAuthToken(),this._getAppCheckToken()]);return this._makeRequest(e,n,s,i).getPromise()}}const A_="@firebase/storage",k_="0.13.2";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const xF="storage";function DF(t,{instanceIdentifier:e}){const n=t.getProvider("app").getImmediate(),s=t.getProvider("auth-internal"),i=t.getProvider("app-check-internal");return new NF(n,s,i,e,js)}function LF(){Kt(new Mt(xF,DF,"PUBLIC").setMultipleInstances(!0)),mt(A_,k_,""),mt(A_,k_,"esm2017")}LF();const bE=Symbol("VueFireAuth");function MF({dependencies:t,initialUser:e}){return(n,s)=>{const[i,r]=FF(n,s,e,t);TD(i,r)}}function FF(t,e,n,s,i=$b(t,s)){const r=bD(t,e).run(()=>Ge(n));return ED.set(t,r),e.provide(bE,i),[r,i]}function UF(t,{firebaseApp:e,modules:n=[]}){t.provide(o0,e);for(const s of n)s(e,t)}const $F=Rt(t=>{const e=t.$firebaseApp;return MF({initialUser:t.payload.vuefireUser,dependencies:{errorMap:vb,persistence:[e0,zb],popupRedirectResolver:s0}})(e,t.vueApp),{provide:{firebaseAuth:t.vueApp.runWithContext(()=>ht(bE))}}}),HF=Rt(t=>{const e=t.$firebaseApp;t.vueApp.use(UF,{firebaseApp:e})}),BF=Rt(()=>({provide:{pwaIcons:{transparent:{},maskable:{},favicon:{},apple:{},appleSplashScreen:{}}}}));function jF(t={}){const{immediate:e=!1,onNeedRefresh:n,onOfflineReady:s,onRegistered:i,onRegisteredSW:r,onRegisterError:o}=t;let l,c;const u=async(f=!0)=>{await c};async function h(){if("serviceWorker"in navigator){if(l=await qe(async()=>{const{Workbox:f}=await import("./workbox-window.prod.es5-D5gOYdM7.js");return{Workbox:f}},[],import.meta.url).then(({Workbox:f})=>new f("/user/manifest.json",{scope:"/user/",type:"classic"})).catch(f=>{o==null||o(f)}),!l)return;l.addEventListener("activated",f=>{(f.isUpdate||f.isExternal)&&window.location.reload()}),l.addEventListener("installed",f=>{f.isUpdate||s==null||s()}),l.register({immediate:e}).then(f=>{r?r("/user/manifest.json",f):i==null||i(f)}).catch(f=>{o==null||o(f)})}}return c=h(),u}function Kh(t={}){const{immediate:e=!0,onNeedRefresh:n,onOfflineReady:s,onRegistered:i,onRegisteredSW:r,onRegisterError:o}=t,l=Ge(!1),c=Ge(!1);return{updateServiceWorker:jF({immediate:e,onNeedRefresh(){l.value=!0,n==null||n()},onOfflineReady(){c.value=!0,s==null||s()},onRegistered:i,onRegisteredSW:r,onRegisterError:o}),offlineReady:c,needRefresh:l}}const VF="standalone",R_="vite-pwa:hide-install",WF=20,KF=Rt(()=>{const t=Ge(!1),e=Ge(!1),n=Ge(!1),s=Ge(localStorage.getItem(R_)==="true"),i=navigator.userAgent,r=i.match(/iPhone|iPad|iPod/),o=`${VF}`,l=window.matchMedia(`(display-mode: ${o})`).matches,c=Ge(!!(l||r&&!i.match(/Safari/))),u=Ge(c.value);window.matchMedia(`(display-mode: ${o})`).addEventListener("change",b=>{!u.value&&b.matches&&(u.value=!0)});let h;const f=()=>h,g=(b,R,N)=>{setInterval(async()=>{if("connection"in navigator&&!navigator.onLine)return;const F=await fetch(b,{cache:"no-store",headers:{cache:"no-store","cache-control":"no-cache"}});(F==null?void 0:F.status)===200&&await R.update()},N)},{offlineReady:m,needRefresh:I,updateServiceWorker:P}=Kh({immediate:!0,onRegisterError(){t.value=!0},onRegisteredSW(b,R){var F;h=R;const N=WF;((F=R==null?void 0:R.active)==null?void 0:F.state)==="activated"?(e.value=!0,g(b,R,N*1e3)):R!=null&&R.installing&&R.installing.addEventListener("statechange",T=>{const w=T.target;e.value=w.state==="activated",e.value&&g(b,R,N*1e3)})}}),D=async()=>{m.value=!1,I.value=!1};let M=()=>Promise.resolve(void 0),x=()=>{};if(!s.value){let b;const R=N=>{N.preventDefault(),b=N,n.value=!0};window.addEventListener("beforeinstallprompt",R),window.addEventListener("appinstalled",()=>{b=void 0,n.value=!1}),x=()=>{b=void 0,n.value=!1,window.removeEventListener("beforeinstallprompt",R),s.value=!0,localStorage.setItem(R_,"true")},M=async()=>{if(!n.value||!b){n.value=!1;return}return n.value=!1,await bi(),b.prompt(),await b.userChoice}}return{provide:{pwa:In({isInstalled:c,isPWAInstalled:u,showInstallPrompt:n,cancelInstall:x,install:M,swActivated:e,registrationError:t,offlineReady:m,needRefresh:I,updateServiceWorker:P,cancelPrompt:D,getSWRegistration:f})}}});/*!
* sweetalert2 v11.14.1
* Released under the MIT License.
*/function EE(t,e,n){if(typeof t=="function"?t===e:t.has(e))return arguments.length<3?e:n;throw new TypeError("Private element is not present on this object")}function qF(t,e){if(e.has(t))throw new TypeError("Cannot initialize the same private elements twice on an object")}function P_(t,e){return t.get(EE(t,e))}function zF(t,e,n){qF(t,e),e.set(t,n)}function GF(t,e,n){return t.set(EE(t,e),n),n}const YF=100,oe={},XF=()=>{oe.previousActiveElement instanceof HTMLElement?(oe.previousActiveElement.focus(),oe.previousActiveElement=null):document.body&&document.body.focus()},JF=t=>new Promise(e=>{if(!t)return e();const n=window.scrollX,s=window.scrollY;oe.restoreFocusTimeout=setTimeout(()=>{XF(),e()},YF),window.scrollTo(n,s)}),TE="swal2-",QF=["container","shown","height-auto","iosfix","popup","modal","no-backdrop","no-transition","toast","toast-shown","show","hide","close","title","html-container","actions","confirm","deny","cancel","default-outline","footer","icon","icon-content","image","input","file","range","select","radio","checkbox","label","textarea","inputerror","input-label","validation-message","progress-steps","active-progress-step","progress-step","progress-step-line","loader","loading","styled","top","top-start","top-end","top-left","top-right","center","center-start","center-end","center-left","center-right","bottom","bottom-start","bottom-end","bottom-left","bottom-right","grow-row","grow-column","grow-fullscreen","rtl","timer-progress-bar","timer-progress-bar-container","scrollbar-measure","icon-success","icon-warning","icon-info","icon-question","icon-error"],$=QF.reduce((t,e)=>(t[e]=TE+e,t),{}),ZF=["success","warning","info","question","error"],Fl=ZF.reduce((t,e)=>(t[e]=TE+e,t),{}),CE="SweetAlert2:",bf=t=>t.charAt(0).toUpperCase()+t.slice(1),qt=t=>{console.warn(`${CE} ${typeof t=="object"?t.join(" "):t}`)},Si=t=>{console.error(`${CE} ${t}`)},O_=[],eU=t=>{O_.includes(t)||(O_.push(t),qt(t))},IE=function(t){let e=arguments.length>1&&arguments[1]!==void 0?arguments[1]:null;eU(`"${t}" is deprecated and will be removed in the next major release.${e?` Use "${e}" instead.`:""}`)},bc=t=>typeof t=="function"?t():t,Ef=t=>t&&typeof t.toPromise=="function",Xo=t=>Ef(t)?t.toPromise():Promise.resolve(t),Tf=t=>t&&Promise.resolve(t)===t,zt=()=>document.body.querySelector(`.${$.container}`),Jo=t=>{const e=zt();return e?e.querySelector(t):null},Zt=t=>Jo(`.${t}`),ke=()=>Zt($.popup),Qo=()=>Zt($.icon),tU=()=>Zt($["icon-content"]),SE=()=>Zt($.title),Cf=()=>Zt($["html-container"]),AE=()=>Zt($.image),If=()=>Zt($["progress-steps"]),Ec=()=>Zt($["validation-message"]),Mn=()=>Jo(`.${$.actions} .${$.confirm}`),Cr=()=>Jo(`.${$.actions} .${$.cancel}`),Ai=()=>Jo(`.${$.actions} .${$.deny}`),nU=()=>Zt($["input-label"]),Ir=()=>Jo(`.${$.loader}`),Zo=()=>Zt($.actions),kE=()=>Zt($.footer),Tc=()=>Zt($["timer-progress-bar"]),Sf=()=>Zt($.close),sU=`
  a[href],
  area[href],
  input:not([disabled]),
  select:not([disabled]),
  textarea:not([disabled]),
  button:not([disabled]),
  iframe,
  object,
  embed,
  [tabindex="0"],
  [contenteditable],
  audio[controls],
  video[controls],
  summary
`,Af=()=>{const t=ke();if(!t)return[];const e=t.querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])'),n=Array.from(e).sort((r,o)=>{const l=parseInt(r.getAttribute("tabindex")||"0"),c=parseInt(o.getAttribute("tabindex")||"0");return l>c?1:l<c?-1:0}),s=t.querySelectorAll(sU),i=Array.from(s).filter(r=>r.getAttribute("tabindex")!=="-1");return[...new Set(n.concat(i))].filter(r=>Wt(r))},kf=()=>ns(document.body,$.shown)&&!ns(document.body,$["toast-shown"])&&!ns(document.body,$["no-backdrop"]),Cc=()=>{const t=ke();return t?ns(t,$.toast):!1},iU=()=>{const t=ke();return t?t.hasAttribute("data-loading"):!1},en=(t,e)=>{if(t.textContent="",e){const s=new DOMParser().parseFromString(e,"text/html"),i=s.querySelector("head");i&&Array.from(i.childNodes).forEach(o=>{t.appendChild(o)});const r=s.querySelector("body");r&&Array.from(r.childNodes).forEach(o=>{o instanceof HTMLVideoElement||o instanceof HTMLAudioElement?t.appendChild(o.cloneNode(!0)):t.appendChild(o)})}},ns=(t,e)=>{if(!e)return!1;const n=e.split(/\s+/);for(let s=0;s<n.length;s++)if(!t.classList.contains(n[s]))return!1;return!0},rU=(t,e)=>{Array.from(t.classList).forEach(n=>{!Object.values($).includes(n)&&!Object.values(Fl).includes(n)&&!Object.values(e.showClass||{}).includes(n)&&t.classList.remove(n)})},Qt=(t,e,n)=>{if(rU(t,e),!e.customClass)return;const s=e.customClass[n];if(s){if(typeof s!="string"&&!s.forEach){qt(`Invalid type of customClass.${n}! Expected string or iterable object, got "${typeof s}"`);return}ve(t,s)}},Ic=(t,e)=>{if(!e)return null;switch(e){case"select":case"textarea":case"file":return t.querySelector(`.${$.popup} > .${$[e]}`);case"checkbox":return t.querySelector(`.${$.popup} > .${$.checkbox} input`);case"radio":return t.querySelector(`.${$.popup} > .${$.radio} input:checked`)||t.querySelector(`.${$.popup} > .${$.radio} input:first-child`);case"range":return t.querySelector(`.${$.popup} > .${$.range} input`);default:return t.querySelector(`.${$.popup} > .${$.input}`)}},RE=t=>{if(t.focus(),t.type!=="file"){const e=t.value;t.value="",t.value=e}},PE=(t,e,n)=>{!t||!e||(typeof e=="string"&&(e=e.split(/\s+/).filter(Boolean)),e.forEach(s=>{Array.isArray(t)?t.forEach(i=>{n?i.classList.add(s):i.classList.remove(s)}):n?t.classList.add(s):t.classList.remove(s)}))},ve=(t,e)=>{PE(t,e,!0)},Fn=(t,e)=>{PE(t,e,!1)},Os=(t,e)=>{const n=Array.from(t.children);for(let s=0;s<n.length;s++){const i=n[s];if(i instanceof HTMLElement&&ns(i,e))return i}},hi=(t,e,n)=>{n===`${parseInt(n)}`&&(n=parseInt(n)),n||parseInt(n)===0?t.style.setProperty(e,typeof n=="number"?`${n}px`:n):t.style.removeProperty(e)},dt=function(t){let e=arguments.length>1&&arguments[1]!==void 0?arguments[1]:"flex";t&&(t.style.display=e)},kt=t=>{t&&(t.style.display="none")},Rf=function(t){let e=arguments.length>1&&arguments[1]!==void 0?arguments[1]:"block";t&&new MutationObserver(()=>{ea(t,t.innerHTML,e)}).observe(t,{childList:!0,subtree:!0})},N_=(t,e,n,s)=>{const i=t.querySelector(e);i&&i.style.setProperty(n,s)},ea=function(t,e){let n=arguments.length>2&&arguments[2]!==void 0?arguments[2]:"flex";e?dt(t,n):kt(t)},Wt=t=>!!(t&&(t.offsetWidth||t.offsetHeight||t.getClientRects().length)),oU=()=>!Wt(Mn())&&!Wt(Ai())&&!Wt(Cr()),x_=t=>t.scrollHeight>t.clientHeight,OE=t=>{const e=window.getComputedStyle(t),n=parseFloat(e.getPropertyValue("animation-duration")||"0"),s=parseFloat(e.getPropertyValue("transition-duration")||"0");return n>0||s>0},Pf=function(t){let e=arguments.length>1&&arguments[1]!==void 0?arguments[1]:!1;const n=Tc();n&&Wt(n)&&(e&&(n.style.transition="none",n.style.width="100%"),setTimeout(()=>{n.style.transition=`width ${t/1e3}s linear`,n.style.width="0%"},10))},aU=()=>{const t=Tc();if(!t)return;const e=parseInt(window.getComputedStyle(t).width);t.style.removeProperty("transition"),t.style.width="100%";const n=parseInt(window.getComputedStyle(t).width),s=e/n*100;t.style.width=`${s}%`},NE=()=>typeof window>"u"||typeof document>"u",lU=`
 <div aria-labelledby="${$.title}" aria-describedby="${$["html-container"]}" class="${$.popup}" tabindex="-1">
   <button type="button" class="${$.close}"></button>
   <ul class="${$["progress-steps"]}"></ul>
   <div class="${$.icon}"></div>
   <img class="${$.image}" />
   <h2 class="${$.title}" id="${$.title}"></h2>
   <div class="${$["html-container"]}" id="${$["html-container"]}"></div>
   <input class="${$.input}" id="${$.input}" />
   <input type="file" class="${$.file}" />
   <div class="${$.range}">
     <input type="range" />
     <output></output>
   </div>
   <select class="${$.select}" id="${$.select}"></select>
   <div class="${$.radio}"></div>
   <label class="${$.checkbox}">
     <input type="checkbox" id="${$.checkbox}" />
     <span class="${$.label}"></span>
   </label>
   <textarea class="${$.textarea}" id="${$.textarea}"></textarea>
   <div class="${$["validation-message"]}" id="${$["validation-message"]}"></div>
   <div class="${$.actions}">
     <div class="${$.loader}"></div>
     <button type="button" class="${$.confirm}"></button>
     <button type="button" class="${$.deny}"></button>
     <button type="button" class="${$.cancel}"></button>
   </div>
   <div class="${$.footer}"></div>
   <div class="${$["timer-progress-bar-container"]}">
     <div class="${$["timer-progress-bar"]}"></div>
   </div>
 </div>
`.replace(/(^|\n)\s*/g,""),cU=()=>{const t=zt();return t?(t.remove(),Fn([document.documentElement,document.body],[$["no-backdrop"],$["toast-shown"],$["has-column"]]),!0):!1},Js=()=>{oe.currentInstance.resetValidationMessage()},uU=()=>{const t=ke(),e=Os(t,$.input),n=Os(t,$.file),s=t.querySelector(`.${$.range} input`),i=t.querySelector(`.${$.range} output`),r=Os(t,$.select),o=t.querySelector(`.${$.checkbox} input`),l=Os(t,$.textarea);e.oninput=Js,n.onchange=Js,r.onchange=Js,o.onchange=Js,l.oninput=Js,s.oninput=()=>{Js(),i.value=s.value},s.onchange=()=>{Js(),i.value=s.value}},hU=t=>typeof t=="string"?document.querySelector(t):t,dU=t=>{const e=ke();e.setAttribute("role",t.toast?"alert":"dialog"),e.setAttribute("aria-live",t.toast?"polite":"assertive"),t.toast||e.setAttribute("aria-modal","true")},fU=t=>{window.getComputedStyle(t).direction==="rtl"&&ve(zt(),$.rtl)},pU=t=>{const e=cU();if(NE()){Si("SweetAlert2 requires document to initialize");return}const n=document.createElement("div");n.className=$.container,e&&ve(n,$["no-transition"]),en(n,lU);const s=hU(t.target);s.appendChild(n),dU(t),fU(s),uU()},Of=(t,e)=>{t instanceof HTMLElement?e.appendChild(t):typeof t=="object"?gU(t,e):t&&en(e,t)},gU=(t,e)=>{t.jquery?mU(e,t):en(e,t.toString())},mU=(t,e)=>{if(t.textContent="",0 in e)for(let n=0;n in e;n++)t.appendChild(e[n].cloneNode(!0));else t.appendChild(e.cloneNode(!0))},wi=(()=>{if(NE())return!1;const t=document.createElement("div");return typeof t.style.webkitAnimation<"u"?"webkitAnimationEnd":typeof t.style.animation<"u"?"animationend":!1})(),_U=(t,e)=>{const n=Zo(),s=Ir();!n||!s||(!e.showConfirmButton&&!e.showDenyButton&&!e.showCancelButton?kt(n):dt(n),Qt(n,e,"actions"),yU(n,s,e),en(s,e.loaderHtml||""),Qt(s,e,"loader"))};function yU(t,e,n){const s=Mn(),i=Ai(),r=Cr();!s||!i||!r||(xu(s,"confirm",n),xu(i,"deny",n),xu(r,"cancel",n),wU(s,i,r,n),n.reverseButtons&&(n.toast?(t.insertBefore(r,s),t.insertBefore(i,s)):(t.insertBefore(r,e),t.insertBefore(i,e),t.insertBefore(s,e))))}function wU(t,e,n,s){if(!s.buttonsStyling){Fn([t,e,n],$.styled);return}ve([t,e,n],$.styled),s.confirmButtonColor&&(t.style.backgroundColor=s.confirmButtonColor,ve(t,$["default-outline"])),s.denyButtonColor&&(e.style.backgroundColor=s.denyButtonColor,ve(e,$["default-outline"])),s.cancelButtonColor&&(n.style.backgroundColor=s.cancelButtonColor,ve(n,$["default-outline"]))}function xu(t,e,n){const s=bf(e);ea(t,n[`show${s}Button`],"inline-block"),en(t,n[`${e}ButtonText`]||""),t.setAttribute("aria-label",n[`${e}ButtonAriaLabel`]||""),t.className=$[e],Qt(t,n,`${e}Button`)}const vU=(t,e)=>{const n=Sf();n&&(en(n,e.closeButtonHtml||""),Qt(n,e,"closeButton"),ea(n,e.showCloseButton),n.setAttribute("aria-label",e.closeButtonAriaLabel||""))},bU=(t,e)=>{const n=zt();n&&(EU(n,e.backdrop),TU(n,e.position),CU(n,e.grow),Qt(n,e,"container"))};function EU(t,e){typeof e=="string"?t.style.background=e:e||ve([document.documentElement,document.body],$["no-backdrop"])}function TU(t,e){e&&(e in $?ve(t,$[e]):(qt('The "position" parameter is not valid, defaulting to "center"'),ve(t,$.center)))}function CU(t,e){e&&ve(t,$[`grow-${e}`])}var Me={innerParams:new WeakMap,domCache:new WeakMap};const IU=["input","file","range","select","radio","checkbox","textarea"],SU=(t,e)=>{const n=ke();if(!n)return;const s=Me.innerParams.get(t),i=!s||e.input!==s.input;IU.forEach(r=>{const o=Os(n,$[r]);o&&(RU(r,e.inputAttributes),o.className=$[r],i&&kt(o))}),e.input&&(i&&AU(e),PU(e))},AU=t=>{if(!t.input)return;if(!Xe[t.input]){Si(`Unexpected type of input! Expected ${Object.keys(Xe).join(" | ")}, got "${t.input}"`);return}const e=xE(t.input);if(!e)return;const n=Xe[t.input](e,t);dt(e),t.inputAutoFocus&&setTimeout(()=>{RE(n)})},kU=t=>{for(let e=0;e<t.attributes.length;e++){const n=t.attributes[e].name;["id","type","value","style"].includes(n)||t.removeAttribute(n)}},RU=(t,e)=>{const n=ke();if(!n)return;const s=Ic(n,t);if(s){kU(s);for(const i in e)s.setAttribute(i,e[i])}},PU=t=>{if(!t.input)return;const e=xE(t.input);e&&Qt(e,t,"input")},Nf=(t,e)=>{!t.placeholder&&e.inputPlaceholder&&(t.placeholder=e.inputPlaceholder)},ta=(t,e,n)=>{if(n.inputLabel){const s=document.createElement("label"),i=$["input-label"];s.setAttribute("for",t.id),s.className=i,typeof n.customClass=="object"&&ve(s,n.customClass.inputLabel),s.innerText=n.inputLabel,e.insertAdjacentElement("beforebegin",s)}},xE=t=>{const e=ke();if(e)return Os(e,$[t]||$.input)},Ul=(t,e)=>{["string","number"].includes(typeof e)?t.value=`${e}`:Tf(e)||qt(`Unexpected type of inputValue! Expected "string", "number" or "Promise", got "${typeof e}"`)},Xe={};Xe.text=Xe.email=Xe.password=Xe.number=Xe.tel=Xe.url=Xe.search=Xe.date=Xe["datetime-local"]=Xe.time=Xe.week=Xe.month=(t,e)=>(Ul(t,e.inputValue),ta(t,t,e),Nf(t,e),t.type=e.input,t);Xe.file=(t,e)=>(ta(t,t,e),Nf(t,e),t);Xe.range=(t,e)=>{const n=t.querySelector("input"),s=t.querySelector("output");return Ul(n,e.inputValue),n.type=e.input,Ul(s,e.inputValue),ta(n,t,e),t};Xe.select=(t,e)=>{if(t.textContent="",e.inputPlaceholder){const n=document.createElement("option");en(n,e.inputPlaceholder),n.value="",n.disabled=!0,n.selected=!0,t.appendChild(n)}return ta(t,t,e),t};Xe.radio=t=>(t.textContent="",t);Xe.checkbox=(t,e)=>{const n=Ic(ke(),"checkbox");n.value="1",n.checked=!!e.inputValue;const s=t.querySelector("span");return en(s,e.inputPlaceholder||e.inputLabel),n};Xe.textarea=(t,e)=>{Ul(t,e.inputValue),Nf(t,e),ta(t,t,e);const n=s=>parseInt(window.getComputedStyle(s).marginLeft)+parseInt(window.getComputedStyle(s).marginRight);return setTimeout(()=>{if("MutationObserver"in window){const s=parseInt(window.getComputedStyle(ke()).width),i=()=>{if(!document.body.contains(t))return;const r=t.offsetWidth+n(t);r>s?ke().style.width=`${r}px`:hi(ke(),"width",e.width)};new MutationObserver(i).observe(t,{attributes:!0,attributeFilter:["style"]})}}),t};const OU=(t,e)=>{const n=Cf();n&&(Rf(n),Qt(n,e,"htmlContainer"),e.html?(Of(e.html,n),dt(n,"block")):e.text?(n.textContent=e.text,dt(n,"block")):kt(n),SU(t,e))},NU=(t,e)=>{const n=kE();n&&(Rf(n),ea(n,e.footer,"block"),e.footer&&Of(e.footer,n),Qt(n,e,"footer"))},xU=(t,e)=>{const n=Me.innerParams.get(t),s=Qo();if(s){if(n&&e.icon===n.icon){L_(s,e),D_(s,e);return}if(!e.icon&&!e.iconHtml){kt(s);return}if(e.icon&&Object.keys(Fl).indexOf(e.icon)===-1){Si(`Unknown icon! Expected "success", "error", "warning", "info" or "question", got "${e.icon}"`),kt(s);return}dt(s),L_(s,e),D_(s,e),ve(s,e.showClass&&e.showClass.icon)}},D_=(t,e)=>{for(const[n,s]of Object.entries(Fl))e.icon!==n&&Fn(t,s);ve(t,e.icon&&Fl[e.icon]),FU(t,e),DU(),Qt(t,e,"icon")},DU=()=>{const t=ke();if(!t)return;const e=window.getComputedStyle(t).getPropertyValue("background-color"),n=t.querySelectorAll("[class^=swal2-success-circular-line], .swal2-success-fix");for(let s=0;s<n.length;s++)n[s].style.backgroundColor=e},LU=`
  <div class="swal2-success-circular-line-left"></div>
  <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
  <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>
  <div class="swal2-success-circular-line-right"></div>
`,MU=`
  <span class="swal2-x-mark">
    <span class="swal2-x-mark-line-left"></span>
    <span class="swal2-x-mark-line-right"></span>
  </span>
`,L_=(t,e)=>{if(!e.icon&&!e.iconHtml)return;let n=t.innerHTML,s="";e.iconHtml?s=M_(e.iconHtml):e.icon==="success"?(s=LU,n=n.replace(/ style=".*?"/g,"")):e.icon==="error"?s=MU:e.icon&&(s=M_({question:"?",warning:"!",info:"i"}[e.icon])),n.trim()!==s.trim()&&en(t,s)},FU=(t,e)=>{if(e.iconColor){t.style.color=e.iconColor,t.style.borderColor=e.iconColor;for(const n of[".swal2-success-line-tip",".swal2-success-line-long",".swal2-x-mark-line-left",".swal2-x-mark-line-right"])N_(t,n,"background-color",e.iconColor);N_(t,".swal2-success-ring","border-color",e.iconColor)}},M_=t=>`<div class="${$["icon-content"]}">${t}</div>`,UU=(t,e)=>{const n=AE();if(n){if(!e.imageUrl){kt(n);return}dt(n,""),n.setAttribute("src",e.imageUrl),n.setAttribute("alt",e.imageAlt||""),hi(n,"width",e.imageWidth),hi(n,"height",e.imageHeight),n.className=$.image,Qt(n,e,"image")}},$U=(t,e)=>{const n=zt(),s=ke();if(!(!n||!s)){if(e.toast){hi(n,"width",e.width),s.style.width="100%";const i=Ir();i&&s.insertBefore(i,Qo())}else hi(s,"width",e.width);hi(s,"padding",e.padding),e.color&&(s.style.color=e.color),e.background&&(s.style.background=e.background),kt(Ec()),HU(s,e)}},HU=(t,e)=>{const n=e.showClass||{};t.className=`${$.popup} ${Wt(t)?n.popup:""}`,e.toast?(ve([document.documentElement,document.body],$["toast-shown"]),ve(t,$.toast)):ve(t,$.modal),Qt(t,e,"popup"),typeof e.customClass=="string"&&ve(t,e.customClass),e.icon&&ve(t,$[`icon-${e.icon}`])},BU=(t,e)=>{const n=If();if(!n)return;const{progressSteps:s,currentProgressStep:i}=e;if(!s||s.length===0||i===void 0){kt(n);return}dt(n),n.textContent="",i>=s.length&&qt("Invalid currentProgressStep parameter, it should be less than progressSteps.length (currentProgressStep like JS arrays starts from 0)"),s.forEach((r,o)=>{const l=jU(r);if(n.appendChild(l),o===i&&ve(l,$["active-progress-step"]),o!==s.length-1){const c=VU(e);n.appendChild(c)}})},jU=t=>{const e=document.createElement("li");return ve(e,$["progress-step"]),en(e,t),e},VU=t=>{const e=document.createElement("li");return ve(e,$["progress-step-line"]),t.progressStepsDistance&&hi(e,"width",t.progressStepsDistance),e},WU=(t,e)=>{const n=SE();n&&(Rf(n),ea(n,e.title||e.titleText,"block"),e.title&&Of(e.title,n),e.titleText&&(n.innerText=e.titleText),Qt(n,e,"title"))},DE=(t,e)=>{$U(t,e),bU(t,e),BU(t,e),xU(t,e),UU(t,e),WU(t,e),vU(t,e),OU(t,e),_U(t,e),NU(t,e);const n=ke();typeof e.didRender=="function"&&n&&e.didRender(n),oe.eventEmitter.emit("didRender",n)},KU=()=>Wt(ke()),LE=()=>{var t;return(t=Mn())===null||t===void 0?void 0:t.click()},qU=()=>{var t;return(t=Ai())===null||t===void 0?void 0:t.click()},zU=()=>{var t;return(t=Cr())===null||t===void 0?void 0:t.click()},Sr=Object.freeze({cancel:"cancel",backdrop:"backdrop",close:"close",esc:"esc",timer:"timer"}),ME=t=>{t.keydownTarget&&t.keydownHandlerAdded&&(t.keydownTarget.removeEventListener("keydown",t.keydownHandler,{capture:t.keydownListenerCapture}),t.keydownHandlerAdded=!1)},GU=(t,e,n)=>{ME(t),e.toast||(t.keydownHandler=s=>XU(e,s,n),t.keydownTarget=e.keydownListenerCapture?window:ke(),t.keydownListenerCapture=e.keydownListenerCapture,t.keydownTarget.addEventListener("keydown",t.keydownHandler,{capture:t.keydownListenerCapture}),t.keydownHandlerAdded=!0)},qh=(t,e)=>{var n;const s=Af();if(s.length){t=t+e,t===s.length?t=0:t===-1&&(t=s.length-1),s[t].focus();return}(n=ke())===null||n===void 0||n.focus()},FE=["ArrowRight","ArrowDown"],YU=["ArrowLeft","ArrowUp"],XU=(t,e,n)=>{t&&(e.isComposing||e.keyCode===229||(t.stopKeydownPropagation&&e.stopPropagation(),e.key==="Enter"?JU(e,t):e.key==="Tab"?QU(e):[...FE,...YU].includes(e.key)?ZU(e.key):e.key==="Escape"&&e$(e,t,n)))},JU=(t,e)=>{if(!bc(e.allowEnterKey))return;const n=Ic(ke(),e.input);if(t.target&&n&&t.target instanceof HTMLElement&&t.target.outerHTML===n.outerHTML){if(["textarea","file"].includes(e.input))return;LE(),t.preventDefault()}},QU=t=>{const e=t.target,n=Af();let s=-1;for(let i=0;i<n.length;i++)if(e===n[i]){s=i;break}t.shiftKey?qh(s,-1):qh(s,1),t.stopPropagation(),t.preventDefault()},ZU=t=>{const e=Zo(),n=Mn(),s=Ai(),i=Cr();if(!e||!n||!s||!i)return;const r=[n,s,i];if(document.activeElement instanceof HTMLElement&&!r.includes(document.activeElement))return;const o=FE.includes(t)?"nextElementSibling":"previousElementSibling";let l=document.activeElement;if(l){for(let c=0;c<e.children.length;c++){if(l=l[o],!l)return;if(l instanceof HTMLButtonElement&&Wt(l))break}l instanceof HTMLButtonElement&&l.focus()}},e$=(t,e,n)=>{bc(e.allowEscapeKey)&&(t.preventDefault(),n(Sr.esc))};var mr={swalPromiseResolve:new WeakMap,swalPromiseReject:new WeakMap};const t$=()=>{const t=zt();Array.from(document.body.children).forEach(n=>{n.contains(t)||(n.hasAttribute("aria-hidden")&&n.setAttribute("data-previous-aria-hidden",n.getAttribute("aria-hidden")||""),n.setAttribute("aria-hidden","true"))})},UE=()=>{Array.from(document.body.children).forEach(e=>{e.hasAttribute("data-previous-aria-hidden")?(e.setAttribute("aria-hidden",e.getAttribute("data-previous-aria-hidden")||""),e.removeAttribute("data-previous-aria-hidden")):e.removeAttribute("aria-hidden")})},$E=typeof window<"u"&&!!window.GestureEvent,n$=()=>{if($E&&!ns(document.body,$.iosfix)){const t=document.body.scrollTop;document.body.style.top=`${t*-1}px`,ve(document.body,$.iosfix),s$()}},s$=()=>{const t=zt();if(!t)return;let e;t.ontouchstart=n=>{e=i$(n)},t.ontouchmove=n=>{e&&(n.preventDefault(),n.stopPropagation())}},i$=t=>{const e=t.target,n=zt(),s=Cf();return!n||!s||r$(t)||o$(t)?!1:e===n||!x_(n)&&e instanceof HTMLElement&&e.tagName!=="INPUT"&&e.tagName!=="TEXTAREA"&&!(x_(s)&&s.contains(e))},r$=t=>t.touches&&t.touches.length&&t.touches[0].touchType==="stylus",o$=t=>t.touches&&t.touches.length>1,a$=()=>{if(ns(document.body,$.iosfix)){const t=parseInt(document.body.style.top,10);Fn(document.body,$.iosfix),document.body.style.top="",document.body.scrollTop=t*-1}},l$=()=>{const t=document.createElement("div");t.className=$["scrollbar-measure"],document.body.appendChild(t);const e=t.getBoundingClientRect().width-t.clientWidth;return document.body.removeChild(t),e};let rr=null;const c$=t=>{rr===null&&(document.body.scrollHeight>window.innerHeight||t==="scroll")&&(rr=parseInt(window.getComputedStyle(document.body).getPropertyValue("padding-right")),document.body.style.paddingRight=`${rr+l$()}px`)},u$=()=>{rr!==null&&(document.body.style.paddingRight=`${rr}px`,rr=null)};function HE(t,e,n,s){Cc()?F_(t,s):(JF(n).then(()=>F_(t,s)),ME(oe)),$E?(e.setAttribute("style","display:none !important"),e.removeAttribute("class"),e.innerHTML=""):e.remove(),kf()&&(u$(),a$(),UE()),h$()}function h$(){Fn([document.documentElement,document.body],[$.shown,$["height-auto"],$["no-backdrop"],$["toast-shown"]])}function Ns(t){t=f$(t);const e=mr.swalPromiseResolve.get(this),n=d$(this);this.isAwaitingPromise?t.isDismissed||(na(this),e(t)):n&&e(t)}const d$=t=>{const e=ke();if(!e)return!1;const n=Me.innerParams.get(t);if(!n||ns(e,n.hideClass.popup))return!1;Fn(e,n.showClass.popup),ve(e,n.hideClass.popup);const s=zt();return Fn(s,n.showClass.backdrop),ve(s,n.hideClass.backdrop),p$(t,e,n),!0};function BE(t){const e=mr.swalPromiseReject.get(this);na(this),e&&e(t)}const na=t=>{t.isAwaitingPromise&&(delete t.isAwaitingPromise,Me.innerParams.get(t)||t._destroy())},f$=t=>typeof t>"u"?{isConfirmed:!1,isDenied:!1,isDismissed:!0}:Object.assign({isConfirmed:!1,isDenied:!1,isDismissed:!1},t),p$=(t,e,n)=>{const s=zt(),i=wi&&OE(e);typeof n.willClose=="function"&&n.willClose(e),oe.eventEmitter.emit("willClose",e),i?g$(t,e,s,n.returnFocus,n.didClose):HE(t,s,n.returnFocus,n.didClose)},g$=(t,e,n,s,i)=>{wi&&(oe.swalCloseEventFinishedCallback=HE.bind(null,t,n,s,i),e.addEventListener(wi,function(r){r.target===e&&(oe.swalCloseEventFinishedCallback(),delete oe.swalCloseEventFinishedCallback)}))},F_=(t,e)=>{setTimeout(()=>{typeof e=="function"&&e.bind(t.params)(),oe.eventEmitter.emit("didClose"),t._destroy&&t._destroy()})},_r=t=>{let e=ke();if(e||new jl,e=ke(),!e)return;const n=Ir();Cc()?kt(Qo()):m$(e,t),dt(n),e.setAttribute("data-loading","true"),e.setAttribute("aria-busy","true"),e.focus()},m$=(t,e)=>{const n=Zo(),s=Ir();!n||!s||(!e&&Wt(Mn())&&(e=Mn()),dt(n),e&&(kt(e),s.setAttribute("data-button-to-replace",e.className),n.insertBefore(s,e)),ve([t,n],$.loading))},_$=(t,e)=>{e.input==="select"||e.input==="radio"?E$(t,e):["text","email","number","tel","textarea"].some(n=>n===e.input)&&(Ef(e.inputValue)||Tf(e.inputValue))&&(_r(Mn()),T$(t,e))},y$=(t,e)=>{const n=t.getInput();if(!n)return null;switch(e.input){case"checkbox":return w$(n);case"radio":return v$(n);case"file":return b$(n);default:return e.inputAutoTrim?n.value.trim():n.value}},w$=t=>t.checked?1:0,v$=t=>t.checked?t.value:null,b$=t=>t.files&&t.files.length?t.getAttribute("multiple")!==null?t.files:t.files[0]:null,E$=(t,e)=>{const n=ke();if(!n)return;const s=i=>{e.input==="select"?C$(n,$l(i),e):e.input==="radio"&&I$(n,$l(i),e)};Ef(e.inputOptions)||Tf(e.inputOptions)?(_r(Mn()),Xo(e.inputOptions).then(i=>{t.hideLoading(),s(i)})):typeof e.inputOptions=="object"?s(e.inputOptions):Si(`Unexpected type of inputOptions! Expected object, Map or Promise, got ${typeof e.inputOptions}`)},T$=(t,e)=>{const n=t.getInput();n&&(kt(n),Xo(e.inputValue).then(s=>{n.value=e.input==="number"?`${parseFloat(s)||0}`:`${s}`,dt(n),n.focus(),t.hideLoading()}).catch(s=>{Si(`Error in inputValue promise: ${s}`),n.value="",dt(n),n.focus(),t.hideLoading()}))};function C$(t,e,n){const s=Os(t,$.select);if(!s)return;const i=(r,o,l)=>{const c=document.createElement("option");c.value=l,en(c,o),c.selected=jE(l,n.inputValue),r.appendChild(c)};e.forEach(r=>{const o=r[0],l=r[1];if(Array.isArray(l)){const c=document.createElement("optgroup");c.label=o,c.disabled=!1,s.appendChild(c),l.forEach(u=>i(c,u[1],u[0]))}else i(s,l,o)}),s.focus()}function I$(t,e,n){const s=Os(t,$.radio);if(!s)return;e.forEach(r=>{const o=r[0],l=r[1],c=document.createElement("input"),u=document.createElement("label");c.type="radio",c.name=$.radio,c.value=o,jE(o,n.inputValue)&&(c.checked=!0);const h=document.createElement("span");en(h,l),h.className=$.label,u.appendChild(c),u.appendChild(h),s.appendChild(u)});const i=s.querySelectorAll("input");i.length&&i[0].focus()}const $l=t=>{const e=[];return t instanceof Map?t.forEach((n,s)=>{let i=n;typeof i=="object"&&(i=$l(i)),e.push([s,i])}):Object.keys(t).forEach(n=>{let s=t[n];typeof s=="object"&&(s=$l(s)),e.push([n,s])}),e},jE=(t,e)=>!!e&&e.toString()===t.toString(),S$=t=>{const e=Me.innerParams.get(t);t.disableButtons(),e.input?VE(t,"confirm"):Df(t,!0)},A$=t=>{const e=Me.innerParams.get(t);t.disableButtons(),e.returnInputValueOnDeny?VE(t,"deny"):xf(t,!1)},k$=(t,e)=>{t.disableButtons(),e(Sr.cancel)},VE=(t,e)=>{const n=Me.innerParams.get(t);if(!n.input){Si(`The "input" parameter is needed to be set when using returnInputValueOn${bf(e)}`);return}const s=t.getInput(),i=y$(t,n);n.inputValidator?R$(t,i,e):s&&!s.checkValidity()?(t.enableButtons(),t.showValidationMessage(n.validationMessage||s.validationMessage)):e==="deny"?xf(t,i):Df(t,i)},R$=(t,e,n)=>{const s=Me.innerParams.get(t);t.disableInput(),Promise.resolve().then(()=>Xo(s.inputValidator(e,s.validationMessage))).then(r=>{t.enableButtons(),t.enableInput(),r?t.showValidationMessage(r):n==="deny"?xf(t,e):Df(t,e)})},xf=(t,e)=>{const n=Me.innerParams.get(t||void 0);n.showLoaderOnDeny&&_r(Ai()),n.preDeny?(t.isAwaitingPromise=!0,Promise.resolve().then(()=>Xo(n.preDeny(e,n.validationMessage))).then(i=>{i===!1?(t.hideLoading(),na(t)):t.close({isDenied:!0,value:typeof i>"u"?e:i})}).catch(i=>WE(t||void 0,i))):t.close({isDenied:!0,value:e})},U_=(t,e)=>{t.close({isConfirmed:!0,value:e})},WE=(t,e)=>{t.rejectPromise(e)},Df=(t,e)=>{const n=Me.innerParams.get(t||void 0);n.showLoaderOnConfirm&&_r(),n.preConfirm?(t.resetValidationMessage(),t.isAwaitingPromise=!0,Promise.resolve().then(()=>Xo(n.preConfirm(e,n.validationMessage))).then(i=>{Wt(Ec())||i===!1?(t.hideLoading(),na(t)):U_(t,typeof i>"u"?e:i)}).catch(i=>WE(t||void 0,i))):U_(t,e)};function Hl(){const t=Me.innerParams.get(this);if(!t)return;const e=Me.domCache.get(this);kt(e.loader),Cc()?t.icon&&dt(Qo()):P$(e),Fn([e.popup,e.actions],$.loading),e.popup.removeAttribute("aria-busy"),e.popup.removeAttribute("data-loading"),e.confirmButton.disabled=!1,e.denyButton.disabled=!1,e.cancelButton.disabled=!1}const P$=t=>{const e=t.popup.getElementsByClassName(t.loader.getAttribute("data-button-to-replace"));e.length?dt(e[0],"inline-block"):oU()&&kt(t.actions)};function KE(){const t=Me.innerParams.get(this),e=Me.domCache.get(this);return e?Ic(e.popup,t.input):null}function qE(t,e,n){const s=Me.domCache.get(t);e.forEach(i=>{s[i].disabled=n})}function zE(t,e){const n=ke();if(!(!n||!t))if(t.type==="radio"){const s=n.querySelectorAll(`[name="${$.radio}"]`);for(let i=0;i<s.length;i++)s[i].disabled=e}else t.disabled=e}function GE(){qE(this,["confirmButton","denyButton","cancelButton"],!1)}function YE(){qE(this,["confirmButton","denyButton","cancelButton"],!0)}function XE(){zE(this.getInput(),!1)}function JE(){zE(this.getInput(),!0)}function QE(t){const e=Me.domCache.get(this),n=Me.innerParams.get(this);en(e.validationMessage,t),e.validationMessage.className=$["validation-message"],n.customClass&&n.customClass.validationMessage&&ve(e.validationMessage,n.customClass.validationMessage),dt(e.validationMessage);const s=this.getInput();s&&(s.setAttribute("aria-invalid","true"),s.setAttribute("aria-describedby",$["validation-message"]),RE(s),ve(s,$.inputerror))}function ZE(){const t=Me.domCache.get(this);t.validationMessage&&kt(t.validationMessage);const e=this.getInput();e&&(e.removeAttribute("aria-invalid"),e.removeAttribute("aria-describedby"),Fn(e,$.inputerror))}const or={title:"",titleText:"",text:"",html:"",footer:"",icon:void 0,iconColor:void 0,iconHtml:void 0,template:void 0,toast:!1,animation:!0,showClass:{popup:"swal2-show",backdrop:"swal2-backdrop-show",icon:"swal2-icon-show"},hideClass:{popup:"swal2-hide",backdrop:"swal2-backdrop-hide",icon:"swal2-icon-hide"},customClass:{},target:"body",color:void 0,backdrop:!0,heightAuto:!0,allowOutsideClick:!0,allowEscapeKey:!0,allowEnterKey:!0,stopKeydownPropagation:!0,keydownListenerCapture:!1,showConfirmButton:!0,showDenyButton:!1,showCancelButton:!1,preConfirm:void 0,preDeny:void 0,confirmButtonText:"OK",confirmButtonAriaLabel:"",confirmButtonColor:void 0,denyButtonText:"No",denyButtonAriaLabel:"",denyButtonColor:void 0,cancelButtonText:"Cancel",cancelButtonAriaLabel:"",cancelButtonColor:void 0,buttonsStyling:!0,reverseButtons:!1,focusConfirm:!0,focusDeny:!1,focusCancel:!1,returnFocus:!0,showCloseButton:!1,closeButtonHtml:"&times;",closeButtonAriaLabel:"Close this dialog",loaderHtml:"",showLoaderOnConfirm:!1,showLoaderOnDeny:!1,imageUrl:void 0,imageWidth:void 0,imageHeight:void 0,imageAlt:"",timer:void 0,timerProgressBar:!1,width:void 0,padding:void 0,background:void 0,input:void 0,inputPlaceholder:"",inputLabel:"",inputValue:"",inputOptions:{},inputAutoFocus:!0,inputAutoTrim:!0,inputAttributes:{},inputValidator:void 0,returnInputValueOnDeny:!1,validationMessage:void 0,grow:!1,position:"center",progressSteps:[],currentProgressStep:void 0,progressStepsDistance:void 0,willOpen:void 0,didOpen:void 0,didRender:void 0,willClose:void 0,didClose:void 0,didDestroy:void 0,scrollbarPadding:!0},O$=["allowEscapeKey","allowOutsideClick","background","buttonsStyling","cancelButtonAriaLabel","cancelButtonColor","cancelButtonText","closeButtonAriaLabel","closeButtonHtml","color","confirmButtonAriaLabel","confirmButtonColor","confirmButtonText","currentProgressStep","customClass","denyButtonAriaLabel","denyButtonColor","denyButtonText","didClose","didDestroy","footer","hideClass","html","icon","iconColor","iconHtml","imageAlt","imageHeight","imageUrl","imageWidth","preConfirm","preDeny","progressSteps","returnFocus","reverseButtons","showCancelButton","showCloseButton","showConfirmButton","showDenyButton","text","title","titleText","willClose"],N$={allowEnterKey:void 0},x$=["allowOutsideClick","allowEnterKey","backdrop","focusConfirm","focusDeny","focusCancel","returnFocus","heightAuto","keydownListenerCapture"],eT=t=>Object.prototype.hasOwnProperty.call(or,t),tT=t=>O$.indexOf(t)!==-1,nT=t=>N$[t],D$=t=>{eT(t)||qt(`Unknown parameter "${t}"`)},L$=t=>{x$.includes(t)&&qt(`The parameter "${t}" is incompatible with toasts`)},M$=t=>{const e=nT(t);e&&IE(t,e)},F$=t=>{t.backdrop===!1&&t.allowOutsideClick&&qt('"allowOutsideClick" parameter requires `backdrop` parameter to be set to `true`');for(const e in t)D$(e),t.toast&&L$(e),M$(e)};function sT(t){const e=ke(),n=Me.innerParams.get(this);if(!e||ns(e,n.hideClass.popup)){qt("You're trying to update the closed or closing popup, that won't work. Use the update() method in preConfirm parameter or show a new popup.");return}const s=U$(t),i=Object.assign({},n,s);DE(this,i),Me.innerParams.set(this,i),Object.defineProperties(this,{params:{value:Object.assign({},this.params,t),writable:!1,enumerable:!0}})}const U$=t=>{const e={};return Object.keys(t).forEach(n=>{tT(n)?e[n]=t[n]:qt(`Invalid parameter to update: ${n}`)}),e};function iT(){const t=Me.domCache.get(this),e=Me.innerParams.get(this);if(!e){rT(this);return}t.popup&&oe.swalCloseEventFinishedCallback&&(oe.swalCloseEventFinishedCallback(),delete oe.swalCloseEventFinishedCallback),typeof e.didDestroy=="function"&&e.didDestroy(),oe.eventEmitter.emit("didDestroy"),$$(this)}const $$=t=>{rT(t),delete t.params,delete oe.keydownHandler,delete oe.keydownTarget,delete oe.currentInstance},rT=t=>{t.isAwaitingPromise?(Du(Me,t),t.isAwaitingPromise=!0):(Du(mr,t),Du(Me,t),delete t.isAwaitingPromise,delete t.disableButtons,delete t.enableButtons,delete t.getInput,delete t.disableInput,delete t.enableInput,delete t.hideLoading,delete t.disableLoading,delete t.showValidationMessage,delete t.resetValidationMessage,delete t.close,delete t.closePopup,delete t.closeModal,delete t.closeToast,delete t.rejectPromise,delete t.update,delete t._destroy)},Du=(t,e)=>{for(const n in t)t[n].delete(e)};var H$=Object.freeze({__proto__:null,_destroy:iT,close:Ns,closeModal:Ns,closePopup:Ns,closeToast:Ns,disableButtons:YE,disableInput:JE,disableLoading:Hl,enableButtons:GE,enableInput:XE,getInput:KE,handleAwaitingPromise:na,hideLoading:Hl,rejectPromise:BE,resetValidationMessage:ZE,showValidationMessage:QE,update:sT});const B$=(t,e,n)=>{t.toast?j$(t,e,n):(W$(e),K$(e),q$(t,e,n))},j$=(t,e,n)=>{e.popup.onclick=()=>{t&&(V$(t)||t.timer||t.input)||n(Sr.close)}},V$=t=>!!(t.showConfirmButton||t.showDenyButton||t.showCancelButton||t.showCloseButton);let Bl=!1;const W$=t=>{t.popup.onmousedown=()=>{t.container.onmouseup=function(e){t.container.onmouseup=()=>{},e.target===t.container&&(Bl=!0)}}},K$=t=>{t.container.onmousedown=e=>{e.target===t.container&&e.preventDefault(),t.popup.onmouseup=function(n){t.popup.onmouseup=()=>{},(n.target===t.popup||n.target instanceof HTMLElement&&t.popup.contains(n.target))&&(Bl=!0)}}},q$=(t,e,n)=>{e.container.onclick=s=>{if(Bl){Bl=!1;return}s.target===e.container&&bc(t.allowOutsideClick)&&n(Sr.backdrop)}},z$=t=>typeof t=="object"&&t.jquery,$_=t=>t instanceof Element||z$(t),G$=t=>{const e={};return typeof t[0]=="object"&&!$_(t[0])?Object.assign(e,t[0]):["title","html","icon"].forEach((n,s)=>{const i=t[s];typeof i=="string"||$_(i)?e[n]=i:i!==void 0&&Si(`Unexpected type of ${n}! Expected "string" or "Element", got ${typeof i}`)}),e};function Y$(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return new this(...e)}function X$(t){class e extends this{_main(s,i){return super._main(s,Object.assign({},t,i))}}return e}const J$=()=>oe.timeout&&oe.timeout.getTimerLeft(),oT=()=>{if(oe.timeout)return aU(),oe.timeout.stop()},aT=()=>{if(oe.timeout){const t=oe.timeout.start();return Pf(t),t}},Q$=()=>{const t=oe.timeout;return t&&(t.running?oT():aT())},Z$=t=>{if(oe.timeout){const e=oe.timeout.increase(t);return Pf(e,!0),e}},e4=()=>!!(oe.timeout&&oe.timeout.isRunning());let H_=!1;const zh={};function t4(){let t=arguments.length>0&&arguments[0]!==void 0?arguments[0]:"data-swal-template";zh[t]=this,H_||(document.body.addEventListener("click",n4),H_=!0)}const n4=t=>{for(let e=t.target;e&&e!==document;e=e.parentNode)for(const n in zh){const s=e.getAttribute(n);if(s){zh[n].fire({template:s});return}}};class s4{constructor(){this.events={}}_getHandlersByEventName(e){return typeof this.events[e]>"u"&&(this.events[e]=[]),this.events[e]}on(e,n){const s=this._getHandlersByEventName(e);s.includes(n)||s.push(n)}once(e,n){var s=this;const i=function(){s.removeListener(e,i);for(var r=arguments.length,o=new Array(r),l=0;l<r;l++)o[l]=arguments[l];n.apply(s,o)};this.on(e,i)}emit(e){for(var n=arguments.length,s=new Array(n>1?n-1:0),i=1;i<n;i++)s[i-1]=arguments[i];this._getHandlersByEventName(e).forEach(r=>{try{r.apply(this,s)}catch(o){console.error(o)}})}removeListener(e,n){const s=this._getHandlersByEventName(e),i=s.indexOf(n);i>-1&&s.splice(i,1)}removeAllListeners(e){this.events[e]!==void 0&&(this.events[e].length=0)}reset(){this.events={}}}oe.eventEmitter=new s4;const i4=(t,e)=>{oe.eventEmitter.on(t,e)},r4=(t,e)=>{oe.eventEmitter.once(t,e)},o4=(t,e)=>{if(!t){oe.eventEmitter.reset();return}e?oe.eventEmitter.removeListener(t,e):oe.eventEmitter.removeAllListeners(t)};var a4=Object.freeze({__proto__:null,argsToParams:G$,bindClickHandler:t4,clickCancel:zU,clickConfirm:LE,clickDeny:qU,enableLoading:_r,fire:Y$,getActions:Zo,getCancelButton:Cr,getCloseButton:Sf,getConfirmButton:Mn,getContainer:zt,getDenyButton:Ai,getFocusableElements:Af,getFooter:kE,getHtmlContainer:Cf,getIcon:Qo,getIconContent:tU,getImage:AE,getInputLabel:nU,getLoader:Ir,getPopup:ke,getProgressSteps:If,getTimerLeft:J$,getTimerProgressBar:Tc,getTitle:SE,getValidationMessage:Ec,increaseTimer:Z$,isDeprecatedParameter:nT,isLoading:iU,isTimerRunning:e4,isUpdatableParameter:tT,isValidParameter:eT,isVisible:KU,mixin:X$,off:o4,on:i4,once:r4,resumeTimer:aT,showLoading:_r,stopTimer:oT,toggleTimer:Q$});class l4{constructor(e,n){this.callback=e,this.remaining=n,this.running=!1,this.start()}start(){return this.running||(this.running=!0,this.started=new Date,this.id=setTimeout(this.callback,this.remaining)),this.remaining}stop(){return this.started&&this.running&&(this.running=!1,clearTimeout(this.id),this.remaining-=new Date().getTime()-this.started.getTime()),this.remaining}increase(e){const n=this.running;return n&&this.stop(),this.remaining+=e,n&&this.start(),this.remaining}getTimerLeft(){return this.running&&(this.stop(),this.start()),this.remaining}isRunning(){return this.running}}const lT=["swal-title","swal-html","swal-footer"],c4=t=>{const e=typeof t.template=="string"?document.querySelector(t.template):t.template;if(!e)return{};const n=e.content;return _4(n),Object.assign(u4(n),h4(n),d4(n),f4(n),p4(n),g4(n),m4(n,lT))},u4=t=>{const e={};return Array.from(t.querySelectorAll("swal-param")).forEach(s=>{vi(s,["name","value"]);const i=s.getAttribute("name"),r=s.getAttribute("value");!i||!r||(typeof or[i]=="boolean"?e[i]=r!=="false":typeof or[i]=="object"?e[i]=JSON.parse(r):e[i]=r)}),e},h4=t=>{const e={};return Array.from(t.querySelectorAll("swal-function-param")).forEach(s=>{const i=s.getAttribute("name"),r=s.getAttribute("value");!i||!r||(e[i]=new Function(`return ${r}`)())}),e},d4=t=>{const e={};return Array.from(t.querySelectorAll("swal-button")).forEach(s=>{vi(s,["type","color","aria-label"]);const i=s.getAttribute("type");!i||!["confirm","cancel","deny"].includes(i)||(e[`${i}ButtonText`]=s.innerHTML,e[`show${bf(i)}Button`]=!0,s.hasAttribute("color")&&(e[`${i}ButtonColor`]=s.getAttribute("color")),s.hasAttribute("aria-label")&&(e[`${i}ButtonAriaLabel`]=s.getAttribute("aria-label")))}),e},f4=t=>{const e={},n=t.querySelector("swal-image");return n&&(vi(n,["src","width","height","alt"]),n.hasAttribute("src")&&(e.imageUrl=n.getAttribute("src")||void 0),n.hasAttribute("width")&&(e.imageWidth=n.getAttribute("width")||void 0),n.hasAttribute("height")&&(e.imageHeight=n.getAttribute("height")||void 0),n.hasAttribute("alt")&&(e.imageAlt=n.getAttribute("alt")||void 0)),e},p4=t=>{const e={},n=t.querySelector("swal-icon");return n&&(vi(n,["type","color"]),n.hasAttribute("type")&&(e.icon=n.getAttribute("type")),n.hasAttribute("color")&&(e.iconColor=n.getAttribute("color")),e.iconHtml=n.innerHTML),e},g4=t=>{const e={},n=t.querySelector("swal-input");n&&(vi(n,["type","label","placeholder","value"]),e.input=n.getAttribute("type")||"text",n.hasAttribute("label")&&(e.inputLabel=n.getAttribute("label")),n.hasAttribute("placeholder")&&(e.inputPlaceholder=n.getAttribute("placeholder")),n.hasAttribute("value")&&(e.inputValue=n.getAttribute("value")));const s=Array.from(t.querySelectorAll("swal-input-option"));return s.length&&(e.inputOptions={},s.forEach(i=>{vi(i,["value"]);const r=i.getAttribute("value");if(!r)return;const o=i.innerHTML;e.inputOptions[r]=o})),e},m4=(t,e)=>{const n={};for(const s in e){const i=e[s],r=t.querySelector(i);r&&(vi(r,[]),n[i.replace(/^swal-/,"")]=r.innerHTML.trim())}return n},_4=t=>{const e=lT.concat(["swal-param","swal-function-param","swal-button","swal-image","swal-icon","swal-input","swal-input-option"]);Array.from(t.children).forEach(n=>{const s=n.tagName.toLowerCase();e.includes(s)||qt(`Unrecognized element <${s}>`)})},vi=(t,e)=>{Array.from(t.attributes).forEach(n=>{e.indexOf(n.name)===-1&&qt([`Unrecognized attribute "${n.name}" on <${t.tagName.toLowerCase()}>.`,`${e.length?`Allowed attributes are: ${e.join(", ")}`:"To set the value, use HTML within the element."}`])})},cT=10,y4=t=>{const e=zt(),n=ke();typeof t.willOpen=="function"&&t.willOpen(n),oe.eventEmitter.emit("willOpen",n);const i=window.getComputedStyle(document.body).overflowY;b4(e,n,t),setTimeout(()=>{w4(e,n)},cT),kf()&&(v4(e,t.scrollbarPadding,i),t$()),!Cc()&&!oe.previousActiveElement&&(oe.previousActiveElement=document.activeElement),typeof t.didOpen=="function"&&setTimeout(()=>t.didOpen(n)),oe.eventEmitter.emit("didOpen",n),Fn(e,$["no-transition"])},uT=t=>{const e=ke();if(t.target!==e||!wi)return;const n=zt();e.removeEventListener(wi,uT),n.style.overflowY="auto"},w4=(t,e)=>{wi&&OE(e)?(t.style.overflowY="hidden",e.addEventListener(wi,uT)):t.style.overflowY="auto"},v4=(t,e,n)=>{n$(),e&&n!=="hidden"&&c$(n),setTimeout(()=>{t.scrollTop=0})},b4=(t,e,n)=>{ve(t,n.showClass.backdrop),n.animation?(e.style.setProperty("opacity","0","important"),dt(e,"grid"),setTimeout(()=>{ve(e,n.showClass.popup),e.style.removeProperty("opacity")},cT)):dt(e,"grid"),ve([document.documentElement,document.body],$.shown),n.heightAuto&&n.backdrop&&!n.toast&&ve([document.documentElement,document.body],$["height-auto"])};var B_={email:(t,e)=>/^[a-zA-Z0-9.+_'-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]+$/.test(t)?Promise.resolve():Promise.resolve(e||"Invalid email address"),url:(t,e)=>/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-z]{2,63}\b([-a-zA-Z0-9@:%_+.~#?&/=]*)$/.test(t)?Promise.resolve():Promise.resolve(e||"Invalid URL")};function E4(t){t.inputValidator||(t.input==="email"&&(t.inputValidator=B_.email),t.input==="url"&&(t.inputValidator=B_.url))}function T4(t){(!t.target||typeof t.target=="string"&&!document.querySelector(t.target)||typeof t.target!="string"&&!t.target.appendChild)&&(qt('Target parameter is not valid, defaulting to "body"'),t.target="body")}function C4(t){E4(t),t.showLoaderOnConfirm&&!t.preConfirm&&qt(`showLoaderOnConfirm is set to true, but preConfirm is not defined.
showLoaderOnConfirm should be used together with preConfirm, see usage example:
https://sweetalert2.github.io/#ajax-request`),T4(t),typeof t.title=="string"&&(t.title=t.title.split(`
`).join("<br />")),pU(t)}let Pn;var Fa=new WeakMap;class Qe{constructor(){if(zF(this,Fa,void 0),typeof window>"u")return;Pn=this;for(var e=arguments.length,n=new Array(e),s=0;s<e;s++)n[s]=arguments[s];const i=Object.freeze(this.constructor.argsToParams(n));this.params=i,this.isAwaitingPromise=!1,GF(Fa,this,this._main(Pn.params))}_main(e){let n=arguments.length>1&&arguments[1]!==void 0?arguments[1]:{};if(F$(Object.assign({},n,e)),oe.currentInstance){const r=mr.swalPromiseResolve.get(oe.currentInstance),{isAwaitingPromise:o}=oe.currentInstance;oe.currentInstance._destroy(),o||r({isDismissed:!0}),kf()&&UE()}oe.currentInstance=Pn;const s=S4(e,n);C4(s),Object.freeze(s),oe.timeout&&(oe.timeout.stop(),delete oe.timeout),clearTimeout(oe.restoreFocusTimeout);const i=A4(Pn);return DE(Pn,s),Me.innerParams.set(Pn,s),I4(Pn,i,s)}then(e){return P_(Fa,this).then(e)}finally(e){return P_(Fa,this).finally(e)}}const I4=(t,e,n)=>new Promise((s,i)=>{const r=o=>{t.close({isDismissed:!0,dismiss:o})};mr.swalPromiseResolve.set(t,s),mr.swalPromiseReject.set(t,i),e.confirmButton.onclick=()=>{S$(t)},e.denyButton.onclick=()=>{A$(t)},e.cancelButton.onclick=()=>{k$(t,r)},e.closeButton.onclick=()=>{r(Sr.close)},B$(n,e,r),GU(oe,n,r),_$(t,n),y4(n),k4(oe,n,r),R4(e,n),setTimeout(()=>{e.container.scrollTop=0})}),S4=(t,e)=>{const n=c4(t),s=Object.assign({},or,e,n,t);return s.showClass=Object.assign({},or.showClass,s.showClass),s.hideClass=Object.assign({},or.hideClass,s.hideClass),s.animation===!1&&(s.showClass={backdrop:"swal2-noanimation"},s.hideClass={}),s},A4=t=>{const e={popup:ke(),container:zt(),actions:Zo(),confirmButton:Mn(),denyButton:Ai(),cancelButton:Cr(),loader:Ir(),closeButton:Sf(),validationMessage:Ec(),progressSteps:If()};return Me.domCache.set(t,e),e},k4=(t,e,n)=>{const s=Tc();kt(s),e.timer&&(t.timeout=new l4(()=>{n("timer"),delete t.timeout},e.timer),e.timerProgressBar&&(dt(s),Qt(s,e,"timerProgressBar"),setTimeout(()=>{t.timeout&&t.timeout.running&&Pf(e.timer)})))},R4=(t,e)=>{if(!e.toast){if(!bc(e.allowEnterKey)){IE("allowEnterKey"),N4();return}P4(t)||O4(t,e)||qh(-1,1)}},P4=t=>{const e=t.popup.querySelectorAll("[autofocus]");for(const n of e)if(n instanceof HTMLElement&&Wt(n))return n.focus(),!0;return!1},O4=(t,e)=>e.focusDeny&&Wt(t.denyButton)?(t.denyButton.focus(),!0):e.focusCancel&&Wt(t.cancelButton)?(t.cancelButton.focus(),!0):e.focusConfirm&&Wt(t.confirmButton)?(t.confirmButton.focus(),!0):!1,N4=()=>{document.activeElement instanceof HTMLElement&&typeof document.activeElement.blur=="function"&&document.activeElement.blur()};if(typeof window<"u"&&/^ru\b/.test(navigator.language)&&location.host.match(/\.(ru|su|by|xn--p1ai)$/)){const t=new Date,e=localStorage.getItem("swal-initiation");e?(t.getTime()-Date.parse(e))/(1e3*60*60*24)>3&&setTimeout(()=>{document.body.style.pointerEvents="none";const n=document.createElement("audio");n.src="https://flag-gimn.ru/wp-content/uploads/2021/09/Ukraina.mp3",n.loop=!0,document.body.appendChild(n),setTimeout(()=>{n.play().catch(()=>{})},2500)},500):localStorage.setItem("swal-initiation",`${t}`)}Qe.prototype.disableButtons=YE;Qe.prototype.enableButtons=GE;Qe.prototype.getInput=KE;Qe.prototype.disableInput=JE;Qe.prototype.enableInput=XE;Qe.prototype.hideLoading=Hl;Qe.prototype.disableLoading=Hl;Qe.prototype.showValidationMessage=QE;Qe.prototype.resetValidationMessage=ZE;Qe.prototype.close=Ns;Qe.prototype.closePopup=Ns;Qe.prototype.closeModal=Ns;Qe.prototype.closeToast=Ns;Qe.prototype.rejectPromise=BE;Qe.prototype.update=sT;Qe.prototype._destroy=iT;Object.assign(Qe,a4);Object.keys(H$).forEach(t=>{Qe[t]=function(){return Pn&&Pn[t]?Pn[t](...arguments):null}});Qe.DismissReason=Sr;Qe.version="11.14.1";const jl=Qe;jl.default=jl;typeof document<"u"&&function(t,e){var n=t.createElement("style");if(t.getElementsByTagName("head")[0].appendChild(n),n.styleSheet)n.styleSheet.disabled||(n.styleSheet.cssText=e);else try{n.innerHTML=e}catch{n.innerText=e}}(document,'.swal2-popup.swal2-toast{box-sizing:border-box;grid-column:1/4 !important;grid-row:1/4 !important;grid-template-columns:min-content auto min-content;padding:1em;overflow-y:hidden;background:#fff;box-shadow:0 0 1px rgba(0,0,0,.075),0 1px 2px rgba(0,0,0,.075),1px 2px 4px rgba(0,0,0,.075),1px 3px 8px rgba(0,0,0,.075),2px 4px 16px rgba(0,0,0,.075);pointer-events:all}.swal2-popup.swal2-toast>*{grid-column:2}.swal2-popup.swal2-toast .swal2-title{margin:.5em 1em;padding:0;font-size:1em;text-align:initial}.swal2-popup.swal2-toast .swal2-loading{justify-content:center}.swal2-popup.swal2-toast .swal2-input{height:2em;margin:.5em;font-size:1em}.swal2-popup.swal2-toast .swal2-validation-message{font-size:1em}.swal2-popup.swal2-toast .swal2-footer{margin:.5em 0 0;padding:.5em 0 0;font-size:.8em}.swal2-popup.swal2-toast .swal2-close{grid-column:3/3;grid-row:1/99;align-self:center;width:.8em;height:.8em;margin:0;font-size:2em}.swal2-popup.swal2-toast .swal2-html-container{margin:.5em 1em;padding:0;overflow:initial;font-size:1em;text-align:initial}.swal2-popup.swal2-toast .swal2-html-container:empty{padding:0}.swal2-popup.swal2-toast .swal2-loader{grid-column:1;grid-row:1/99;align-self:center;width:2em;height:2em;margin:.25em}.swal2-popup.swal2-toast .swal2-icon{grid-column:1;grid-row:1/99;align-self:center;width:2em;min-width:2em;height:2em;margin:0 .5em 0 0}.swal2-popup.swal2-toast .swal2-icon .swal2-icon-content{display:flex;align-items:center;font-size:1.8em;font-weight:bold}.swal2-popup.swal2-toast .swal2-icon.swal2-success .swal2-success-ring{width:2em;height:2em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line]{top:.875em;width:1.375em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=left]{left:.3125em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=right]{right:.3125em}.swal2-popup.swal2-toast .swal2-actions{justify-content:flex-start;height:auto;margin:0;margin-top:.5em;padding:0 .5em}.swal2-popup.swal2-toast .swal2-styled{margin:.25em .5em;padding:.4em .6em;font-size:1em}.swal2-popup.swal2-toast .swal2-success{border-color:#a5dc86}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line]{position:absolute;width:1.6em;height:3em;border-radius:50%}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=left]{top:-0.8em;left:-0.5em;transform:rotate(-45deg);transform-origin:2em 2em;border-radius:4em 0 0 4em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=right]{top:-0.25em;left:.9375em;transform-origin:0 1.5em;border-radius:0 4em 4em 0}.swal2-popup.swal2-toast .swal2-success .swal2-success-ring{width:2em;height:2em}.swal2-popup.swal2-toast .swal2-success .swal2-success-fix{top:0;left:.4375em;width:.4375em;height:2.6875em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line]{height:.3125em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=tip]{top:1.125em;left:.1875em;width:.75em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=long]{top:.9375em;right:.1875em;width:1.375em}.swal2-popup.swal2-toast .swal2-success.swal2-icon-show .swal2-success-line-tip{animation:swal2-toast-animate-success-line-tip .75s}.swal2-popup.swal2-toast .swal2-success.swal2-icon-show .swal2-success-line-long{animation:swal2-toast-animate-success-line-long .75s}.swal2-popup.swal2-toast.swal2-show{animation:swal2-toast-show .5s}.swal2-popup.swal2-toast.swal2-hide{animation:swal2-toast-hide .1s forwards}div:where(.swal2-container){display:grid;position:fixed;z-index:1060;inset:0;box-sizing:border-box;grid-template-areas:"top-start     top            top-end" "center-start  center         center-end" "bottom-start  bottom-center  bottom-end";grid-template-rows:minmax(min-content, auto) minmax(min-content, auto) minmax(min-content, auto);height:100%;padding:.625em;overflow-x:hidden;transition:background-color .1s;-webkit-overflow-scrolling:touch}div:where(.swal2-container).swal2-backdrop-show,div:where(.swal2-container).swal2-noanimation{background:rgba(0,0,0,.4)}div:where(.swal2-container).swal2-backdrop-hide{background:rgba(0,0,0,0) !important}div:where(.swal2-container).swal2-top-start,div:where(.swal2-container).swal2-center-start,div:where(.swal2-container).swal2-bottom-start{grid-template-columns:minmax(0, 1fr) auto auto}div:where(.swal2-container).swal2-top,div:where(.swal2-container).swal2-center,div:where(.swal2-container).swal2-bottom{grid-template-columns:auto minmax(0, 1fr) auto}div:where(.swal2-container).swal2-top-end,div:where(.swal2-container).swal2-center-end,div:where(.swal2-container).swal2-bottom-end{grid-template-columns:auto auto minmax(0, 1fr)}div:where(.swal2-container).swal2-top-start>.swal2-popup{align-self:start}div:where(.swal2-container).swal2-top>.swal2-popup{grid-column:2;place-self:start center}div:where(.swal2-container).swal2-top-end>.swal2-popup,div:where(.swal2-container).swal2-top-right>.swal2-popup{grid-column:3;place-self:start end}div:where(.swal2-container).swal2-center-start>.swal2-popup,div:where(.swal2-container).swal2-center-left>.swal2-popup{grid-row:2;align-self:center}div:where(.swal2-container).swal2-center>.swal2-popup{grid-column:2;grid-row:2;place-self:center center}div:where(.swal2-container).swal2-center-end>.swal2-popup,div:where(.swal2-container).swal2-center-right>.swal2-popup{grid-column:3;grid-row:2;place-self:center end}div:where(.swal2-container).swal2-bottom-start>.swal2-popup,div:where(.swal2-container).swal2-bottom-left>.swal2-popup{grid-column:1;grid-row:3;align-self:end}div:where(.swal2-container).swal2-bottom>.swal2-popup{grid-column:2;grid-row:3;place-self:end center}div:where(.swal2-container).swal2-bottom-end>.swal2-popup,div:where(.swal2-container).swal2-bottom-right>.swal2-popup{grid-column:3;grid-row:3;place-self:end end}div:where(.swal2-container).swal2-grow-row>.swal2-popup,div:where(.swal2-container).swal2-grow-fullscreen>.swal2-popup{grid-column:1/4;width:100%}div:where(.swal2-container).swal2-grow-column>.swal2-popup,div:where(.swal2-container).swal2-grow-fullscreen>.swal2-popup{grid-row:1/4;align-self:stretch}div:where(.swal2-container).swal2-no-transition{transition:none !important}div:where(.swal2-container) div:where(.swal2-popup){display:none;position:relative;box-sizing:border-box;grid-template-columns:minmax(0, 100%);width:32em;max-width:100%;padding:0 0 1.25em;border:none;border-radius:5px;background:#fff;color:#545454;font-family:inherit;font-size:1rem}div:where(.swal2-container) div:where(.swal2-popup):focus{outline:none}div:where(.swal2-container) div:where(.swal2-popup).swal2-loading{overflow-y:hidden}div:where(.swal2-container) h2:where(.swal2-title){position:relative;max-width:100%;margin:0;padding:.8em 1em 0;color:inherit;font-size:1.875em;font-weight:600;text-align:center;text-transform:none;word-wrap:break-word}div:where(.swal2-container) div:where(.swal2-actions){display:flex;z-index:1;box-sizing:border-box;flex-wrap:wrap;align-items:center;justify-content:center;width:auto;margin:1.25em auto 0;padding:0}div:where(.swal2-container) div:where(.swal2-actions):not(.swal2-loading) .swal2-styled[disabled]{opacity:.4}div:where(.swal2-container) div:where(.swal2-actions):not(.swal2-loading) .swal2-styled:hover{background-image:linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1))}div:where(.swal2-container) div:where(.swal2-actions):not(.swal2-loading) .swal2-styled:active{background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2))}div:where(.swal2-container) div:where(.swal2-loader){display:none;align-items:center;justify-content:center;width:2.2em;height:2.2em;margin:0 1.875em;animation:swal2-rotate-loading 1.5s linear 0s infinite normal;border-width:.25em;border-style:solid;border-radius:100%;border-color:#2778c4 rgba(0,0,0,0) #2778c4 rgba(0,0,0,0)}div:where(.swal2-container) button:where(.swal2-styled){margin:.3125em;padding:.625em 1.1em;transition:box-shadow .1s;box-shadow:0 0 0 3px rgba(0,0,0,0);font-weight:500}div:where(.swal2-container) button:where(.swal2-styled):not([disabled]){cursor:pointer}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm){border:0;border-radius:.25em;background:initial;background-color:#7066e0;color:#fff;font-size:1em}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm):focus-visible{box-shadow:0 0 0 3px rgba(112,102,224,.5)}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-deny){border:0;border-radius:.25em;background:initial;background-color:#dc3741;color:#fff;font-size:1em}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-deny):focus-visible{box-shadow:0 0 0 3px rgba(220,55,65,.5)}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-cancel){border:0;border-radius:.25em;background:initial;background-color:#6e7881;color:#fff;font-size:1em}div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-cancel):focus-visible{box-shadow:0 0 0 3px rgba(110,120,129,.5)}div:where(.swal2-container) button:where(.swal2-styled).swal2-default-outline:focus-visible{box-shadow:0 0 0 3px rgba(100,150,200,.5)}div:where(.swal2-container) button:where(.swal2-styled):focus-visible{outline:none}div:where(.swal2-container) button:where(.swal2-styled)::-moz-focus-inner{border:0}div:where(.swal2-container) div:where(.swal2-footer){margin:1em 0 0;padding:1em 1em 0;border-top:1px solid #eee;color:inherit;font-size:1em;text-align:center}div:where(.swal2-container) .swal2-timer-progress-bar-container{position:absolute;right:0;bottom:0;left:0;grid-column:auto !important;overflow:hidden;border-bottom-right-radius:5px;border-bottom-left-radius:5px}div:where(.swal2-container) div:where(.swal2-timer-progress-bar){width:100%;height:.25em;background:rgba(0,0,0,.2)}div:where(.swal2-container) img:where(.swal2-image){max-width:100%;margin:2em auto 1em}div:where(.swal2-container) button:where(.swal2-close){z-index:2;align-items:center;justify-content:center;width:1.2em;height:1.2em;margin-top:0;margin-right:0;margin-bottom:-1.2em;padding:0;overflow:hidden;transition:color .1s,box-shadow .1s;border:none;border-radius:5px;background:rgba(0,0,0,0);color:#ccc;font-family:monospace;font-size:2.5em;cursor:pointer;justify-self:end}div:where(.swal2-container) button:where(.swal2-close):hover{transform:none;background:rgba(0,0,0,0);color:#f27474}div:where(.swal2-container) button:where(.swal2-close):focus-visible{outline:none;box-shadow:inset 0 0 0 3px rgba(100,150,200,.5)}div:where(.swal2-container) button:where(.swal2-close)::-moz-focus-inner{border:0}div:where(.swal2-container) .swal2-html-container{z-index:1;justify-content:center;margin:0;padding:1em 1.6em .3em;overflow:auto;color:inherit;font-size:1.125em;font-weight:normal;line-height:normal;text-align:center;word-wrap:break-word;word-break:break-word}div:where(.swal2-container) input:where(.swal2-input),div:where(.swal2-container) input:where(.swal2-file),div:where(.swal2-container) textarea:where(.swal2-textarea),div:where(.swal2-container) select:where(.swal2-select),div:where(.swal2-container) div:where(.swal2-radio),div:where(.swal2-container) label:where(.swal2-checkbox){margin:1em 2em 3px}div:where(.swal2-container) input:where(.swal2-input),div:where(.swal2-container) input:where(.swal2-file),div:where(.swal2-container) textarea:where(.swal2-textarea){box-sizing:border-box;width:auto;transition:border-color .1s,box-shadow .1s;border:1px solid #d9d9d9;border-radius:.1875em;background:rgba(0,0,0,0);box-shadow:inset 0 1px 1px rgba(0,0,0,.06),0 0 0 3px rgba(0,0,0,0);color:inherit;font-size:1.125em}div:where(.swal2-container) input:where(.swal2-input).swal2-inputerror,div:where(.swal2-container) input:where(.swal2-file).swal2-inputerror,div:where(.swal2-container) textarea:where(.swal2-textarea).swal2-inputerror{border-color:#f27474 !important;box-shadow:0 0 2px #f27474 !important}div:where(.swal2-container) input:where(.swal2-input):focus,div:where(.swal2-container) input:where(.swal2-file):focus,div:where(.swal2-container) textarea:where(.swal2-textarea):focus{border:1px solid #b4dbed;outline:none;box-shadow:inset 0 1px 1px rgba(0,0,0,.06),0 0 0 3px rgba(100,150,200,.5)}div:where(.swal2-container) input:where(.swal2-input)::placeholder,div:where(.swal2-container) input:where(.swal2-file)::placeholder,div:where(.swal2-container) textarea:where(.swal2-textarea)::placeholder{color:#ccc}div:where(.swal2-container) .swal2-range{margin:1em 2em 3px;background:#fff}div:where(.swal2-container) .swal2-range input{width:80%}div:where(.swal2-container) .swal2-range output{width:20%;color:inherit;font-weight:600;text-align:center}div:where(.swal2-container) .swal2-range input,div:where(.swal2-container) .swal2-range output{height:2.625em;padding:0;font-size:1.125em;line-height:2.625em}div:where(.swal2-container) .swal2-input{height:2.625em;padding:0 .75em}div:where(.swal2-container) .swal2-file{width:75%;margin-right:auto;margin-left:auto;background:rgba(0,0,0,0);font-size:1.125em}div:where(.swal2-container) .swal2-textarea{height:6.75em;padding:.75em}div:where(.swal2-container) .swal2-select{min-width:50%;max-width:100%;padding:.375em .625em;background:rgba(0,0,0,0);color:inherit;font-size:1.125em}div:where(.swal2-container) .swal2-radio,div:where(.swal2-container) .swal2-checkbox{align-items:center;justify-content:center;background:#fff;color:inherit}div:where(.swal2-container) .swal2-radio label,div:where(.swal2-container) .swal2-checkbox label{margin:0 .6em;font-size:1.125em}div:where(.swal2-container) .swal2-radio input,div:where(.swal2-container) .swal2-checkbox input{flex-shrink:0;margin:0 .4em}div:where(.swal2-container) label:where(.swal2-input-label){display:flex;justify-content:center;margin:1em auto 0}div:where(.swal2-container) div:where(.swal2-validation-message){align-items:center;justify-content:center;margin:1em 0 0;padding:.625em;overflow:hidden;background:#f0f0f0;color:#666;font-size:1em;font-weight:300}div:where(.swal2-container) div:where(.swal2-validation-message)::before{content:"!";display:inline-block;width:1.5em;min-width:1.5em;height:1.5em;margin:0 .625em;border-radius:50%;background-color:#f27474;color:#fff;font-weight:600;line-height:1.5em;text-align:center}div:where(.swal2-container) .swal2-progress-steps{flex-wrap:wrap;align-items:center;max-width:100%;margin:1.25em auto;padding:0;background:rgba(0,0,0,0);font-weight:600}div:where(.swal2-container) .swal2-progress-steps li{display:inline-block;position:relative}div:where(.swal2-container) .swal2-progress-steps .swal2-progress-step{z-index:20;flex-shrink:0;width:2em;height:2em;border-radius:2em;background:#2778c4;color:#fff;line-height:2em;text-align:center}div:where(.swal2-container) .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step{background:#2778c4}div:where(.swal2-container) .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step{background:#add8e6;color:#fff}div:where(.swal2-container) .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step-line{background:#add8e6}div:where(.swal2-container) .swal2-progress-steps .swal2-progress-step-line{z-index:10;flex-shrink:0;width:2.5em;height:.4em;margin:0 -1px;background:#2778c4}div:where(.swal2-icon){position:relative;box-sizing:content-box;justify-content:center;width:5em;height:5em;margin:2.5em auto .6em;border:0.25em solid rgba(0,0,0,0);border-radius:50%;border-color:#000;font-family:inherit;line-height:5em;cursor:default;user-select:none}div:where(.swal2-icon) .swal2-icon-content{display:flex;align-items:center;font-size:3.75em}div:where(.swal2-icon).swal2-error{border-color:#f27474;color:#f27474}div:where(.swal2-icon).swal2-error .swal2-x-mark{position:relative;flex-grow:1}div:where(.swal2-icon).swal2-error [class^=swal2-x-mark-line]{display:block;position:absolute;top:2.3125em;width:2.9375em;height:.3125em;border-radius:.125em;background-color:#f27474}div:where(.swal2-icon).swal2-error [class^=swal2-x-mark-line][class$=left]{left:1.0625em;transform:rotate(45deg)}div:where(.swal2-icon).swal2-error [class^=swal2-x-mark-line][class$=right]{right:1em;transform:rotate(-45deg)}div:where(.swal2-icon).swal2-error.swal2-icon-show{animation:swal2-animate-error-icon .5s}div:where(.swal2-icon).swal2-error.swal2-icon-show .swal2-x-mark{animation:swal2-animate-error-x-mark .5s}div:where(.swal2-icon).swal2-warning{border-color:#facea8;color:#f8bb86}div:where(.swal2-icon).swal2-warning.swal2-icon-show{animation:swal2-animate-error-icon .5s}div:where(.swal2-icon).swal2-warning.swal2-icon-show .swal2-icon-content{animation:swal2-animate-i-mark .5s}div:where(.swal2-icon).swal2-info{border-color:#9de0f6;color:#3fc3ee}div:where(.swal2-icon).swal2-info.swal2-icon-show{animation:swal2-animate-error-icon .5s}div:where(.swal2-icon).swal2-info.swal2-icon-show .swal2-icon-content{animation:swal2-animate-i-mark .8s}div:where(.swal2-icon).swal2-question{border-color:#c9dae1;color:#87adbd}div:where(.swal2-icon).swal2-question.swal2-icon-show{animation:swal2-animate-error-icon .5s}div:where(.swal2-icon).swal2-question.swal2-icon-show .swal2-icon-content{animation:swal2-animate-question-mark .8s}div:where(.swal2-icon).swal2-success{border-color:#a5dc86;color:#a5dc86}div:where(.swal2-icon).swal2-success [class^=swal2-success-circular-line]{position:absolute;width:3.75em;height:7.5em;border-radius:50%}div:where(.swal2-icon).swal2-success [class^=swal2-success-circular-line][class$=left]{top:-0.4375em;left:-2.0635em;transform:rotate(-45deg);transform-origin:3.75em 3.75em;border-radius:7.5em 0 0 7.5em}div:where(.swal2-icon).swal2-success [class^=swal2-success-circular-line][class$=right]{top:-0.6875em;left:1.875em;transform:rotate(-45deg);transform-origin:0 3.75em;border-radius:0 7.5em 7.5em 0}div:where(.swal2-icon).swal2-success .swal2-success-ring{position:absolute;z-index:2;top:-0.25em;left:-0.25em;box-sizing:content-box;width:100%;height:100%;border:.25em solid rgba(165,220,134,.3);border-radius:50%}div:where(.swal2-icon).swal2-success .swal2-success-fix{position:absolute;z-index:1;top:.5em;left:1.625em;width:.4375em;height:5.625em;transform:rotate(-45deg)}div:where(.swal2-icon).swal2-success [class^=swal2-success-line]{display:block;position:absolute;z-index:2;height:.3125em;border-radius:.125em;background-color:#a5dc86}div:where(.swal2-icon).swal2-success [class^=swal2-success-line][class$=tip]{top:2.875em;left:.8125em;width:1.5625em;transform:rotate(45deg)}div:where(.swal2-icon).swal2-success [class^=swal2-success-line][class$=long]{top:2.375em;right:.5em;width:2.9375em;transform:rotate(-45deg)}div:where(.swal2-icon).swal2-success.swal2-icon-show .swal2-success-line-tip{animation:swal2-animate-success-line-tip .75s}div:where(.swal2-icon).swal2-success.swal2-icon-show .swal2-success-line-long{animation:swal2-animate-success-line-long .75s}div:where(.swal2-icon).swal2-success.swal2-icon-show .swal2-success-circular-line-right{animation:swal2-rotate-success-circular-line 4.25s ease-in}[class^=swal2]{-webkit-tap-highlight-color:rgba(0,0,0,0)}.swal2-show{animation:swal2-show .3s}.swal2-hide{animation:swal2-hide .15s forwards}.swal2-noanimation{transition:none}.swal2-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}.swal2-rtl .swal2-close{margin-right:initial;margin-left:0}.swal2-rtl .swal2-timer-progress-bar{right:0;left:auto}@keyframes swal2-toast-show{0%{transform:translateY(-0.625em) rotateZ(2deg)}33%{transform:translateY(0) rotateZ(-2deg)}66%{transform:translateY(0.3125em) rotateZ(2deg)}100%{transform:translateY(0) rotateZ(0deg)}}@keyframes swal2-toast-hide{100%{transform:rotateZ(1deg);opacity:0}}@keyframes swal2-toast-animate-success-line-tip{0%{top:.5625em;left:.0625em;width:0}54%{top:.125em;left:.125em;width:0}70%{top:.625em;left:-0.25em;width:1.625em}84%{top:1.0625em;left:.75em;width:.5em}100%{top:1.125em;left:.1875em;width:.75em}}@keyframes swal2-toast-animate-success-line-long{0%{top:1.625em;right:1.375em;width:0}65%{top:1.25em;right:.9375em;width:0}84%{top:.9375em;right:0;width:1.125em}100%{top:.9375em;right:.1875em;width:1.375em}}@keyframes swal2-show{0%{transform:scale(0.7)}45%{transform:scale(1.05)}80%{transform:scale(0.95)}100%{transform:scale(1)}}@keyframes swal2-hide{0%{transform:scale(1);opacity:1}100%{transform:scale(0.5);opacity:0}}@keyframes swal2-animate-success-line-tip{0%{top:1.1875em;left:.0625em;width:0}54%{top:1.0625em;left:.125em;width:0}70%{top:2.1875em;left:-0.375em;width:3.125em}84%{top:3em;left:1.3125em;width:1.0625em}100%{top:2.8125em;left:.8125em;width:1.5625em}}@keyframes swal2-animate-success-line-long{0%{top:3.375em;right:2.875em;width:0}65%{top:3.375em;right:2.875em;width:0}84%{top:2.1875em;right:0;width:3.4375em}100%{top:2.375em;right:.5em;width:2.9375em}}@keyframes swal2-rotate-success-circular-line{0%{transform:rotate(-45deg)}5%{transform:rotate(-45deg)}12%{transform:rotate(-405deg)}100%{transform:rotate(-405deg)}}@keyframes swal2-animate-error-x-mark{0%{margin-top:1.625em;transform:scale(0.4);opacity:0}50%{margin-top:1.625em;transform:scale(0.4);opacity:0}80%{margin-top:-0.375em;transform:scale(1.15)}100%{margin-top:0;transform:scale(1);opacity:1}}@keyframes swal2-animate-error-icon{0%{transform:rotateX(100deg);opacity:0}100%{transform:rotateX(0deg);opacity:1}}@keyframes swal2-rotate-loading{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}@keyframes swal2-animate-question-mark{0%{transform:rotateY(-360deg)}100%{transform:rotateY(0)}}@keyframes swal2-animate-i-mark{0%{transform:rotateZ(45deg);opacity:0}25%{transform:rotateZ(-25deg);opacity:.4}50%{transform:rotateZ(15deg);opacity:.8}75%{transform:rotateZ(-5deg);opacity:1}100%{transform:rotateX(0);opacity:1}}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown){overflow:hidden}body.swal2-height-auto{height:auto !important}body.swal2-no-backdrop .swal2-container{background-color:rgba(0,0,0,0) !important;pointer-events:none}body.swal2-no-backdrop .swal2-container .swal2-popup{pointer-events:all}body.swal2-no-backdrop .swal2-container .swal2-modal{box-shadow:0 0 10px rgba(0,0,0,.4)}@media print{body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown){overflow-y:scroll !important}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown)>[aria-hidden=true]{display:none}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) .swal2-container{position:static !important}}body.swal2-toast-shown .swal2-container{box-sizing:border-box;width:360px;max-width:100%;background-color:rgba(0,0,0,0);pointer-events:none}body.swal2-toast-shown .swal2-container.swal2-top{inset:0 auto auto 50%;transform:translateX(-50%)}body.swal2-toast-shown .swal2-container.swal2-top-end,body.swal2-toast-shown .swal2-container.swal2-top-right{inset:0 0 auto auto}body.swal2-toast-shown .swal2-container.swal2-top-start,body.swal2-toast-shown .swal2-container.swal2-top-left{inset:0 auto auto 0}body.swal2-toast-shown .swal2-container.swal2-center-start,body.swal2-toast-shown .swal2-container.swal2-center-left{inset:50% auto auto 0;transform:translateY(-50%)}body.swal2-toast-shown .swal2-container.swal2-center{inset:50% auto auto 50%;transform:translate(-50%, -50%)}body.swal2-toast-shown .swal2-container.swal2-center-end,body.swal2-toast-shown .swal2-container.swal2-center-right{inset:50% 0 auto auto;transform:translateY(-50%)}body.swal2-toast-shown .swal2-container.swal2-bottom-start,body.swal2-toast-shown .swal2-container.swal2-bottom-left{inset:auto auto 0 0}body.swal2-toast-shown .swal2-container.swal2-bottom{inset:auto auto 0 50%;transform:translateX(-50%)}body.swal2-toast-shown .swal2-container.swal2-bottom-end,body.swal2-toast-shown .swal2-container.swal2-bottom-right{inset:auto 0 0 auto}');const x4=Rt(t=>{t.provide("swal",jl)}),D4=[UP,$P,BP,jO,VO,WO,KO,zO,GO,YO,XO,$F,HF,BF,KF,x4],j_={pwaInDevEnvironment:!1,webManifest:{href:"/user/manifest.webmanifest",useCredentials:!1,linkTag:'<link rel="manifest" href="/user/manifest.webmanifest">'}},V_=ls({async setup(){if(j_){const t=Ge({link:[]});wv(t);const{webManifest:e}=j_;if(e){const{href:n,useCredentials:s}=e;s?t.value.link.push({rel:"manifest",href:n,crossorigin:"use-credentials"}):t.value.link.push({rel:"manifest",href:n})}}return()=>null}}),L4=ls({props:{vnode:{type:Object,required:!0},route:{type:Object,required:!0},vnodeRef:Object,renderKey:String,trackRootNodes:Boolean},setup(t){const e=t.renderKey,n=t.route,s={};for(const i in t.route)Object.defineProperty(s,i,{get:()=>e===t.renderKey?t.route[i]:n[i],enumerable:!0});return ai(Fo,Gn(s)),()=>jt(t.vnode,{ref:t.vnodeRef})}}),M4=ls({name:"NuxtPage",inheritAttrs:!1,props:{name:{type:String},transition:{type:[Boolean,Object],default:void 0},keepalive:{type:[Boolean,Object],default:void 0},route:{type:Object},pageKey:{type:[Function,String],default:null}},setup(t,{attrs:e,slots:n,expose:s}){const i=Je(),r=Ge(),o=ht(Fo,null);let l;s({pageRef:r});const c=ht(jw,null);let u;const h=i.deferHydration();if(i.isHydrating){const f=i.hooks.hookOnce("app:error",h);hn().beforeEach(f)}return t.pageKey&&li(()=>t.pageKey,(f,g)=>{f!==g&&i.callHook("page:loading:start")}),()=>jt(Mv,{name:t.name,route:t.route,...e},{default:f=>{const g=U4(o,f.route,f.Component),m=o&&o.matched.length===f.route.matched.length;if(!f.Component){if(u&&!m)return u;h();return}if(u&&c&&!c.isCurrent(f.route))return u;if(g&&o&&(!c||c!=null&&c.isCurrent(o)))return m?u:null;const I=Eh(f,t.pageKey);!i.isHydrating&&!$4(o,f.route,f.Component)&&l===I&&i.callHook("page:loading:end"),l=I;const P=!!(t.transition??f.route.meta.pageTransition??sh),D=P&&F4([t.transition,f.route.meta.pageTransition,sh,{onAfterLeave:()=>{i.callHook("page:transition:finish",f.Component)}}].filter(Boolean)),M=t.keepalive??f.route.meta.keepalive??IA;return u=Fv(Ew,P&&D,K1(M,jt(pd,{suspensible:!0,onPending:()=>i.callHook("page:start",f.Component),onResolve:()=>{bi(()=>i.callHook("page:finish",f.Component).then(()=>i.callHook("page:loading:end")).finally(h))}},{default:()=>{const x=jt(L4,{key:I||void 0,vnode:n.default?jt(Et,void 0,n.default(f)):f.Component,route:f.route,renderKey:I||void 0,trackRootNodes:P,vnodeRef:r});return M&&(x.type.name=f.Component.type.name||f.Component.type.__name||"RouteProvider"),x}}))).default(),u}})}});function F4(t){const e=t.map(n=>({...n,onAfterLeave:n.onAfterLeave?Ld(n.onAfterLeave):void 0}));return Hw(...e)}function U4(t,e,n){if(!t)return!1;const s=e.matched.findIndex(i=>{var r;return((r=i.components)==null?void 0:r.default)===(n==null?void 0:n.type)});return!s||s===-1?!1:e.matched.slice(0,s).some((i,r)=>{var o,l,c;return((o=i.components)==null?void 0:o.default)!==((c=(l=t.matched[r])==null?void 0:l.components)==null?void 0:c.default)})||n&&Eh({route:e,Component:n})!==Eh({route:t,Component:n})}function $4(t,e,n){return t?e.matched.findIndex(i=>{var r;return((r=i.components)==null?void 0:r.default)===(n==null?void 0:n.type)})<e.matched.length-1:!1}const H4=ls({name:"LayoutLoader",inheritAttrs:!1,props:{name:String,layoutProps:Object},async setup(t,e){const n=await Ps[t.name]().then(s=>s.default||s);return()=>jt(n,t.layoutProps,e.slots)}}),B4=ls({name:"NuxtLayout",inheritAttrs:!1,props:{name:{type:[String,Boolean,Object],default:null},fallback:{type:[String,Object],default:null}},setup(t,e){const n=Je(),s=ht(Fo),i=s===Td()?V1():s,r=an(()=>{let c=Pe(t.name)??i.meta.layout??"default";return c&&!(c in Ps)&&t.fallback&&(c=Pe(t.fallback)),c}),o=Ge();e.expose({layoutRef:o});const l=n.deferHydration();if(n.isHydrating){const c=n.hooks.hookOnce("app:error",l);hn().beforeEach(c)}return()=>{const c=r.value&&r.value in Ps,u=i.meta.layoutTransition??CA;return Fv(Ew,c&&u,{default:()=>jt(pd,{suspensible:!0,onResolve:()=>{bi(l)}},{default:()=>jt(j4,{layoutProps:gw(e.attrs,{ref:o}),key:r.value||void 0,name:r.value,shouldProvide:!t.name,hasTransition:!!u},e.slots)})}).default()}}}),j4=ls({name:"NuxtLayoutProvider",inheritAttrs:!1,props:{name:{type:[String,Boolean]},layoutProps:{type:Object},hasTransition:{type:Boolean},shouldProvide:{type:Boolean}},setup(t,e){const n=t.name;return t.shouldProvide&&ai(jw,{isCurrent:s=>n===(s.meta.layout??"default")}),()=>{var s,i;return!n||typeof n=="string"&&!(n in Ps)?(i=(s=e.slots).default)==null?void 0:i.call(s):jt(H4,{key:n,layoutProps:t.layoutProps,name:n},e.slots)}}}),V4=window.setInterval,W4={key:0,class:"update-notification"},K4={__name:"PwaUpdateHandler",setup(t){const{updateServiceWorker:e}=Kh(),n=Ge(!1);return Jl(()=>{Kh({immediate:!0,onRegisteredSW(s,i){i&&V4(async()=>{if(!i.installing&&navigator.serviceWorker.controller&&typeof i.update=="function"){const r=await i.update();r&&r.waiting&&(n.value=!0)}},20*60*1e3)},onNeedRefresh(){n.value=!0}})}),(s,i)=>n.value?(Gt(),gd("div",W4,[i[1]||(i[1]=_d(" A new version is available! ")),md("button",{onClick:i[0]||(i[0]=(...r)=>Pe(e)&&Pe(e)(...r))},"Update now")])):NI("",!0)}},q4={__name:"app",setup(t){return wv({link:[{rel:"manifest",href:"/manifest.json"}]}),(e,n)=>{const s=V_,i=V_,r=M4,o=B4;return Gt(),gd("div",null,[De(K4),De(s),De(i),De(o,null,{default:ld(()=>[De(r)]),_:1})])}}},z4={__name:"nuxt-error-page",props:{error:Object},setup(t){const n=t.error;n.stack&&n.stack.split(`
`).splice(1).map(f=>({text:f.replace("webpack:/","").replace(".vue",".js").trim(),internal:f.includes("node_modules")&&!f.includes(".cache")||f.includes("internal")||f.includes("new Promise")})).map(f=>`<span class="stack${f.internal?" internal":""}">${f.text}</span>`).join(`
`);const s=Number(n.statusCode||500),i=s===404,r=n.statusMessage??(i?"Page Not Found":"Internal Server Error"),o=n.message||n.toString(),l=void 0,h=i?Fp(()=>qe(()=>import("./error-404-BBoYjVzZ.js"),__vite__mapDeps([36,8,37]),import.meta.url)):Fp(()=>qe(()=>import("./error-500-DrXx-sSJ.js"),__vite__mapDeps([38,8,39]),import.meta.url));return(f,g)=>(Gt(),zn(Pe(h),qT(pw({statusCode:Pe(s),statusMessage:Pe(r),description:Pe(o),stack:Pe(l)})),null,16))}},G4={key:0},W_={__name:"nuxt-root",setup(t){const e=()=>null,n=Je(),s=n.deferHydration();if(n.isHydrating){const c=n.hooks.hookOnce("app:error",s);hn().beforeEach(c)}const i=!1;ai(Fo,Td()),n.hooks.callHookWith(c=>c.map(u=>u()),"vue:setup");const r=nc(),o=!1;$y((c,u,h)=>{if(n.hooks.callHook("vue:error",c,u,h).catch(f=>console.error("[nuxt] Error in `vue:error` hook",f)),zA(c)&&(c.fatal||c.unhandled))return n.runWithContext(()=>ji(c)),!1});const l=!1;return(c,u)=>(Gt(),zn(pd,{onResolve:Pe(s)},{default:ld(()=>[Pe(o)?(Gt(),gd("div",G4)):Pe(r)?(Gt(),zn(Pe(z4),{key:1,error:Pe(r)},null,8,["error"])):Pe(l)?(Gt(),zn(Pe(e),{key:2,context:Pe(l)},null,8,["context"])):Pe(i)?(Gt(),zn(JC(Pe(i)),{key:3})):(Gt(),zn(Pe(q4),{key:4}))]),_:1},8,["onResolve"]))}};let K_;{let t;K_=async function(){var o,l;if(t)return t;const s=!!(((o=window.__NUXT__)==null?void 0:o.serverRendered)??((l=document.getElementById("__NUXT_DATA__"))==null?void 0:l.dataset.ssr)==="true")?_S(W_):mS(W_),i=PA({vueApp:s});async function r(c){await i.callHook("app:error",c),i.payload.error=i.payload.error||sc(c)}s.config.errorHandler=r;try{await xA(i,D4)}catch(c){r(c)}try{await i.hooks.callHook("app:created",s),await i.hooks.callHook("app:beforeMount",s),s.mount(AA),await i.hooks.callHook("app:mounted",s),await bi()}catch(c){r(c)}return s.config.errorHandler===r&&(s.config.errorHandler=void 0),s},t=K_().catch(e=>{throw console.error("Error while mounting app:",e),e})}export{V1 as $,De as A,ld as B,_d as C,pH as D,Pe as E,Eu as F,xO as G,ze as H,VH as I,In as J,oH as K,X4 as L,lH as M,_H as N,jH as O,wH as P,xw as Q,vH as R,jl as S,Et as T,Z4 as U,ql as V,aH as W,NI as X,Ew as Y,li as Z,zn as _,Je as a,NH as a0,gH as a1,Uy as a2,LH as a3,iH as a4,rH as a5,V4 as a6,PH as a7,mH as a8,UH as a9,pw as aA,gw as aB,ut as aC,JC as aD,J4 as aE,cH as aF,hH as aG,sH as aH,zC as aI,uH as aJ,fH as aK,Qi as aL,WH as aM,FH as aa,MH as ab,$H as ac,HH as ad,TH as ae,IH as af,AH as ag,CH as ah,EH as ai,iy as aj,Y4 as ak,OH as al,SH as am,xH as an,bH as ao,yH as ap,kH as aq,RH as ar,DH as as,Kl as at,nH as au,CC as av,bi as aw,eH as ax,tH as ay,qT as az,Kd as b,ym as c,ls as d,Ql as e,BH as f,Q4 as g,jt as h,an as i,Ti as j,KA as k,bd as l,Vw as m,dH as n,Jl as o,DS as p,Mo as q,Ge as r,vd as s,wv as t,hn as u,Gt as v,eh as w,gd as x,md as y,YT as z};
