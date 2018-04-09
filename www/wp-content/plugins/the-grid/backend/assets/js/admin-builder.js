/*jshint forin: false */
/*global jQuery:false */
/*global tg_skin_fonts */
/*global tg_skin_settings */
/*global tg_admin_global_var:false */
/*global TG_element_color:false */
/*global TG_skin_elements */
/*global TG_field_value */
/*global TOMB_RequiredField */
/*global date_to_string */
/*global removeDiacritics */
/*global WebFont */

/* jshint ignore:start */
// jQuery remove accent (http://stackoverflow.com/questions/990904/remove-accents-diacritics-in-a-string-in-javascript)
function removeDiacritics(a){return a.replace(/[^\u0000-\u007E]/g,function(a){return diacriticsMap[a]||a})}for(var defaultDiacriticsRemovalMap=[{base:"A",letters:"A\u24b6\uff21\xc0\xc1\xc2\u1ea6\u1ea4\u1eaa\u1ea8\xc3\u0100\u0102\u1eb0\u1eae\u1eb4\u1eb2\u0226\u01e0\xc4\u01de\u1ea2\xc5\u01fa\u01cd\u0200\u0202\u1ea0\u1eac\u1eb6\u1e00\u0104\u023a\u2c6f"},{base:"AA",letters:"\ua732"},{base:"AE",letters:"\xc6\u01fc\u01e2"},{base:"AO",letters:"\ua734"},{base:"AU",letters:"\ua736"},{base:"AV",letters:"\ua738\ua73a"},{base:"AY",letters:"\ua73c"},{base:"B",letters:"B\u24b7\uff22\u1e02\u1e04\u1e06\u0243\u0182\u0181"},{base:"C",letters:"C\u24b8\uff23\u0106\u0108\u010a\u010c\xc7\u1e08\u0187\u023b\ua73e"},{base:"D",letters:"D\u24b9\uff24\u1e0a\u010e\u1e0c\u1e10\u1e12\u1e0e\u0110\u018b\u018a\u0189\ua779"},{base:"DZ",letters:"\u01f1\u01c4"},{base:"Dz",letters:"\u01f2\u01c5"},{base:"E",letters:"E\u24ba\uff25\xc8\xc9\xca\u1ec0\u1ebe\u1ec4\u1ec2\u1ebc\u0112\u1e14\u1e16\u0114\u0116\xcb\u1eba\u011a\u0204\u0206\u1eb8\u1ec6\u0228\u1e1c\u0118\u1e18\u1e1a\u0190\u018e"},{base:"F",letters:"F\u24bb\uff26\u1e1e\u0191\ua77b"},{base:"G",letters:"G\u24bc\uff27\u01f4\u011c\u1e20\u011e\u0120\u01e6\u0122\u01e4\u0193\ua7a0\ua77d\ua77e"},{base:"H",letters:"H\u24bd\uff28\u0124\u1e22\u1e26\u021e\u1e24\u1e28\u1e2a\u0126\u2c67\u2c75\ua78d"},{base:"I",letters:"I\u24be\uff29\xcc\xcd\xce\u0128\u012a\u012c\u0130\xcf\u1e2e\u1ec8\u01cf\u0208\u020a\u1eca\u012e\u1e2c\u0197"},{base:"J",letters:"J\u24bf\uff2a\u0134\u0248"},{base:"K",letters:"K\u24c0\uff2b\u1e30\u01e8\u1e32\u0136\u1e34\u0198\u2c69\ua740\ua742\ua744\ua7a2"},{base:"L",letters:"L\u24c1\uff2c\u013f\u0139\u013d\u1e36\u1e38\u013b\u1e3c\u1e3a\u0141\u023d\u2c62\u2c60\ua748\ua746\ua780"},{base:"LJ",letters:"\u01c7"},{base:"Lj",letters:"\u01c8"},{base:"M",letters:"M\u24c2\uff2d\u1e3e\u1e40\u1e42\u2c6e\u019c"},{base:"N",letters:"N\u24c3\uff2e\u01f8\u0143\xd1\u1e44\u0147\u1e46\u0145\u1e4a\u1e48\u0220\u019d\ua790\ua7a4"},{base:"NJ",letters:"\u01ca"},{base:"Nj",letters:"\u01cb"},{base:"O",letters:"O\u24c4\uff2f\xd2\xd3\xd4\u1ed2\u1ed0\u1ed6\u1ed4\xd5\u1e4c\u022c\u1e4e\u014c\u1e50\u1e52\u014e\u022e\u0230\xd6\u022a\u1ece\u0150\u01d1\u020c\u020e\u01a0\u1edc\u1eda\u1ee0\u1ede\u1ee2\u1ecc\u1ed8\u01ea\u01ec\xd8\u01fe\u0186\u019f\ua74a\ua74c"},{base:"OI",letters:"\u01a2"},{base:"OO",letters:"\ua74e"},{base:"OU",letters:"\u0222"},{base:"OE",letters:"\x8c\u0152"},{base:"oe",letters:"\x9c\u0153"},{base:"P",letters:"P\u24c5\uff30\u1e54\u1e56\u01a4\u2c63\ua750\ua752\ua754"},{base:"Q",letters:"Q\u24c6\uff31\ua756\ua758\u024a"},{base:"R",letters:"R\u24c7\uff32\u0154\u1e58\u0158\u0210\u0212\u1e5a\u1e5c\u0156\u1e5e\u024c\u2c64\ua75a\ua7a6\ua782"},{base:"S",letters:"S\u24c8\uff33\u1e9e\u015a\u1e64\u015c\u1e60\u0160\u1e66\u1e62\u1e68\u0218\u015e\u2c7e\ua7a8\ua784"},{base:"T",letters:"T\u24c9\uff34\u1e6a\u0164\u1e6c\u021a\u0162\u1e70\u1e6e\u0166\u01ac\u01ae\u023e\ua786"},{base:"TZ",letters:"\ua728"},{base:"U",letters:"U\u24ca\uff35\xd9\xda\xdb\u0168\u1e78\u016a\u1e7a\u016c\xdc\u01db\u01d7\u01d5\u01d9\u1ee6\u016e\u0170\u01d3\u0214\u0216\u01af\u1eea\u1ee8\u1eee\u1eec\u1ef0\u1ee4\u1e72\u0172\u1e76\u1e74\u0244"},{base:"V",letters:"V\u24cb\uff36\u1e7c\u1e7e\u01b2\ua75e\u0245"},{base:"VY",letters:"\ua760"},{base:"W",letters:"W\u24cc\uff37\u1e80\u1e82\u0174\u1e86\u1e84\u1e88\u2c72"},{base:"X",letters:"X\u24cd\uff38\u1e8a\u1e8c"},{base:"Y",letters:"Y\u24ce\uff39\u1ef2\xdd\u0176\u1ef8\u0232\u1e8e\u0178\u1ef6\u1ef4\u01b3\u024e\u1efe"},{base:"Z",letters:"Z\u24cf\uff3a\u0179\u1e90\u017b\u017d\u1e92\u1e94\u01b5\u0224\u2c7f\u2c6b\ua762"},{base:"a",letters:"a\u24d0\uff41\u1e9a\xe0\xe1\xe2\u1ea7\u1ea5\u1eab\u1ea9\xe3\u0101\u0103\u1eb1\u1eaf\u1eb5\u1eb3\u0227\u01e1\xe4\u01df\u1ea3\xe5\u01fb\u01ce\u0201\u0203\u1ea1\u1ead\u1eb7\u1e01\u0105\u2c65\u0250"},{base:"aa",letters:"\ua733"},{base:"ae",letters:"\xe6\u01fd\u01e3"},{base:"ao",letters:"\ua735"},{base:"au",letters:"\ua737"},{base:"av",letters:"\ua739\ua73b"},{base:"ay",letters:"\ua73d"},{base:"b",letters:"b\u24d1\uff42\u1e03\u1e05\u1e07\u0180\u0183\u0253"},{base:"c",letters:"c\u24d2\uff43\u0107\u0109\u010b\u010d\xe7\u1e09\u0188\u023c\ua73f\u2184"},{base:"d",letters:"d\u24d3\uff44\u1e0b\u010f\u1e0d\u1e11\u1e13\u1e0f\u0111\u018c\u0256\u0257\ua77a"},{base:"dz",letters:"\u01f3\u01c6"},{base:"e",letters:"e\u24d4\uff45\xe8\xe9\xea\u1ec1\u1ebf\u1ec5\u1ec3\u1ebd\u0113\u1e15\u1e17\u0115\u0117\xeb\u1ebb\u011b\u0205\u0207\u1eb9\u1ec7\u0229\u1e1d\u0119\u1e19\u1e1b\u0247\u025b\u01dd"},{base:"f",letters:"f\u24d5\uff46\u1e1f\u0192\ua77c"},{base:"g",letters:"g\u24d6\uff47\u01f5\u011d\u1e21\u011f\u0121\u01e7\u0123\u01e5\u0260\ua7a1\u1d79\ua77f"},{base:"h",letters:"h\u24d7\uff48\u0125\u1e23\u1e27\u021f\u1e25\u1e29\u1e2b\u1e96\u0127\u2c68\u2c76\u0265"},{base:"hv",letters:"\u0195"},{base:"i",letters:"i\u24d8\uff49\xec\xed\xee\u0129\u012b\u012d\xef\u1e2f\u1ec9\u01d0\u0209\u020b\u1ecb\u012f\u1e2d\u0268\u0131"},{base:"j",letters:"j\u24d9\uff4a\u0135\u01f0\u0249"},{base:"k",letters:"k\u24da\uff4b\u1e31\u01e9\u1e33\u0137\u1e35\u0199\u2c6a\ua741\ua743\ua745\ua7a3"},{base:"l",letters:"l\u24db\uff4c\u0140\u013a\u013e\u1e37\u1e39\u013c\u1e3d\u1e3b\u017f\u0142\u019a\u026b\u2c61\ua749\ua781\ua747"},{base:"lj",letters:"\u01c9"},{base:"m",letters:"m\u24dc\uff4d\u1e3f\u1e41\u1e43\u0271\u026f"},{base:"n",letters:"n\u24dd\uff4e\u01f9\u0144\xf1\u1e45\u0148\u1e47\u0146\u1e4b\u1e49\u019e\u0272\u0149\ua791\ua7a5"},{base:"nj",letters:"\u01cc"},{base:"o",letters:"o\u24de\uff4f\xf2\xf3\xf4\u1ed3\u1ed1\u1ed7\u1ed5\xf5\u1e4d\u022d\u1e4f\u014d\u1e51\u1e53\u014f\u022f\u0231\xf6\u022b\u1ecf\u0151\u01d2\u020d\u020f\u01a1\u1edd\u1edb\u1ee1\u1edf\u1ee3\u1ecd\u1ed9\u01eb\u01ed\xf8\u01ff\u0254\ua74b\ua74d\u0275"},{base:"oi",letters:"\u01a3"},{base:"ou",letters:"\u0223"},{base:"oo",letters:"\ua74f"},{base:"p",letters:"p\u24df\uff50\u1e55\u1e57\u01a5\u1d7d\ua751\ua753\ua755"},{base:"q",letters:"q\u24e0\uff51\u024b\ua757\ua759"},{base:"r",letters:"r\u24e1\uff52\u0155\u1e59\u0159\u0211\u0213\u1e5b\u1e5d\u0157\u1e5f\u024d\u027d\ua75b\ua7a7\ua783"},{base:"s",letters:"s\u24e2\uff53\xdf\u015b\u1e65\u015d\u1e61\u0161\u1e67\u1e63\u1e69\u0219\u015f\u023f\ua7a9\ua785\u1e9b"},{base:"t",letters:"t\u24e3\uff54\u1e6b\u1e97\u0165\u1e6d\u021b\u0163\u1e71\u1e6f\u0167\u01ad\u0288\u2c66\ua787"},{base:"tz",letters:"\ua729"},{base:"u",letters:"u\u24e4\uff55\xf9\xfa\xfb\u0169\u1e79\u016b\u1e7b\u016d\xfc\u01dc\u01d8\u01d6\u01da\u1ee7\u016f\u0171\u01d4\u0215\u0217\u01b0\u1eeb\u1ee9\u1eef\u1eed\u1ef1\u1ee5\u1e73\u0173\u1e77\u1e75\u0289"},{base:"v",letters:"v\u24e5\uff56\u1e7d\u1e7f\u028b\ua75f\u028c"},{base:"vy",letters:"\ua761"},{base:"w",letters:"w\u24e6\uff57\u1e81\u1e83\u0175\u1e87\u1e85\u1e98\u1e89\u2c73"},{base:"x",letters:"x\u24e7\uff58\u1e8b\u1e8d"},{base:"y",letters:"y\u24e8\uff59\u1ef3\xfd\u0177\u1ef9\u0233\u1e8f\xff\u1ef7\u1e99\u1ef5\u01b4\u024f\u1eff"},{base:"z",letters:"z\u24e9\uff5a\u017a\u1e91\u017c\u017e\u1e93\u1e95\u01b6\u0225\u0240\u2c6c\ua763"}],diacriticsMap={},i=0;i<defaultDiacriticsRemovalMap.length;i++)for(var letters=defaultDiacriticsRemovalMap[i].letters,j=0;j<letters.length;j++)diacriticsMap[letters[j]]=defaultDiacriticsRemovalMap[i].base;  

// jQuery date as php
if("undefined"==typeof date_to_string)var date_to_string=function(a,b){a=String(a),b=new Date;for(var c={a:function(a){return a.getHours()<12?"am":"pm"},A:function(a){return a.getHours()<12?"AM":"PM"},B:function(a){return("000"+Math.floor((60*a.getHours()*60+60*(a.getMinutes()+60+a.getTimezoneOffset())+a.getSeconds())/86.4)%1e3).slice(-3)},c:function(a){return date_to_string("Y-m-d\\TH:i:s",a)},d:function(a){return(a.getDate()<10?"0":"")+a.getDate()},D:function(a){switch(a.getDay()){case 0:return"Sun";case 1:return"Mon";case 2:return"Tue";case 3:return"Wed";case 4:return"Thu";case 5:return"Fri";case 6:return"Sat"}},e:function(a){var b=parseInt(Math.abs(a.getTimezoneOffset()/60),10),c=Math.abs(a.getTimezoneOffset()%60);return((new Date).getTimezoneOffset()<0?"+":"-")+(10>b?"0":"")+b+(10>c?"0":"")+c},F:function(a){switch(a.getMonth()){case 0:return"January";case 1:return"February";case 2:return"March";case 3:return"April";case 4:return"May";case 5:return"June";case 6:return"July";case 7:return"August";case 8:return"September";case 9:return"October";case 10:return"November";case 11:return"December"}},g:function(a){return a.getHours()>12?a.getHours()-12:a.getHours()},G:function(a){return a.getHours()},h:function(a){var b=a.getHours()>12?a.getHours()-12:a.getHours();return(10>b?"0":"")+b},H:function(a){return(a.getHours()<10?"0":"")+a.getHours()},i:function(a){return(a.getMinutes()<10?"0":"")+a.getMinutes()},I:function(a){return a.getTimezoneOffset()<Math.max(new Date(a.getFullYear(),0,1).getTimezoneOffset(),new Date(a.getFullYear(),6,1).getTimezoneOffset())?1:0},j:function(a){return a.getDate()},l:function(a){switch(a.getDay()){case 0:return"Sunday";case 1:return"Monday";case 2:return"Tuesday";case 3:return"Wednesday";case 4:return"Thursday";case 5:return"Friday";case 6:return"Saturday"}},L:function(a){return 1==new Date(a.getFullYear(),1,29).getMonth()?1:0},m:function(a){return(a.getMonth()+1<10?"0":"")+(a.getMonth()+1)},M:function(a){switch(a.getMonth()){case 0:return"Jan";case 1:return"Feb";case 2:return"Mar";case 3:return"Apr";case 4:return"May";case 5:return"Jun";case 6:return"Jul";case 7:return"Aug";case 8:return"Sep";case 9:return"Oct";case 10:return"Nov";case 11:return"Dec"}},n:function(a){return a.getMonth()+1},N:function(a){return 0===a.getDay()?7:a.getDay()},o:function(a){return a.getWeekYear()},O:function(a){var b=parseInt(Math.abs(a.getTimezoneOffset()/60),10),c=Math.abs(a.getTimezoneOffset()%60);return((new Date).getTimezoneOffset()<0?"+":"-")+(10>b?"0":"")+b+(10>c?"0":"")+c},P:function(a){var b=parseInt(Math.abs(a.getTimezoneOffset()/60),10),c=Math.abs(a.getTimezoneOffset()%60);return((new Date).getTimezoneOffset()<0?"+":"-")+(10>b?"0":"")+b+":"+(10>c?"0":"")+c},r:function(a){return date_to_string("D, d M Y H:i:s O",a)},s:function(a){return(a.getSeconds()<10?"0":"")+a.getSeconds()},S:function(a){switch(a.getDate()){case 1:case 21:case 31:return"st";case 2:case 22:return"nd";case 3:case 23:return"rd";default:return"th"}},t:function(a){return new Date(a.getFullYear(),a.getMonth()+1,0).getDate()},T:function(a){var b=String(a).match(/\(([^\)]+)\)$/)||String(a).match(/([A-Z]+) [\d]{4}$/);return b&&(b=b[1].match(/[A-Z]/g).join("")),b},u:function(a){return 1e3*a.getMilliseconds()},U:function(a){return Math.round(a.getTime()/1e3)},w:function(a){return a.getDay()},W:function(a){return a.getWeek()},y:function(a){return String(a.getFullYear()).substring(2,4)},Y:function(a){return a.getFullYear()},z:function(a){return Math.floor((a.getTime()-new Date(a.getFullYear(),0,1).getTime())/864e5)},Z:function(a){return(a.getTimezoneOffset()<0?"+":"-")+24*a.getTimezoneOffset()}},d="",e=!1,f=0;f<a.length;f++)e||"\\"!=a.substring(f,f+1)?e||"undefined"==typeof c[a.substring(f,f+1)]?(d+=String(a.substring(f,f+1)),e=!1):d+=String(c[a.substring(f,f+1)](b)):e=!0;return d};Date.prototype.getWeek=function(){var a=new Date(this.valueOf()),b=(this.getDay()+6)%7;a.setDate(a.getDate()-b+3);var c=a.valueOf();return a.setMonth(0,1),4!=a.getDay()&&a.setMonth(0,1+(4-a.getDay()+7)%7),1+Math.ceil((c-a)/6048e5)},Date.prototype.getWeekYear=function(){var a=new Date(this.valueOf());return a.setDate(a.getDate()-(this.getDay()+6)%7+3),a.getFullYear()};

/* Web Font Loader v1.6.24 - (c) Adobe Systems, Google. License: Apache 2.0 */
(function(){function aa(a,b,d){return a.call.apply(a.bind,arguments)}function ba(a,b,d){if(!a)throw Error();if(2<arguments.length){var c=Array.prototype.slice.call(arguments,2);return function(){var d=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(d,c);return a.apply(b,d)}}return function(){return a.apply(b,arguments)}}function p(a,b,d){p=Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?aa:ba;return p.apply(null,arguments)}var q=Date.now||function(){return+new Date};function ca(a,b){this.a=a;this.m=b||a;this.c=this.m.document}var da=!!window.FontFace;function t(a,b,d,c){b=a.c.createElement(b);if(d)for(var e in d)d.hasOwnProperty(e)&&("style"==e?b.style.cssText=d[e]:b.setAttribute(e,d[e]));c&&b.appendChild(a.c.createTextNode(c));return b}function u(a,b,d){a=a.c.getElementsByTagName(b)[0];a||(a=document.documentElement);a.insertBefore(d,a.lastChild)}function v(a){a.parentNode&&a.parentNode.removeChild(a)}function w(a,b,d){b=b||[];d=d||[];for(var c=a.className.split(/\s+/),e=0;e<b.length;e+=1){for(var f=!1,g=0;g<c.length;g+=1)if(b[e]===c[g]){f=!0;break}f||c.push(b[e])}b=[];for(e=0;e<c.length;e+=1){f=!1;for(g=0;g<d.length;g+=1)if(c[e]===d[g]){f=!0;break}f||b.push(c[e])}a.className=b.join(" ").replace(/\s+/g," ").replace(/^\s+|\s+$/,"")}function y(a,b){for(var d=a.className.split(/\s+/),c=0,e=d.length;c<e;c++)if(d[c]==b)return!0;return!1}function z(a){if("string"===typeof a.f)return a.f;var b=a.m.location.protocol;"about:"==b&&(b=a.a.location.protocol);return"https:"==b?"https:":"http:"}function ea(a){return a.m.location.hostname||a.a.location.hostname}function A(a,b,d){function c(){k&&e&&f&&(k(g),k=null)}b=t(a,"link",{rel:"stylesheet",href:b,media:"all"});var e=!1,f=!0,g=null,k=d||null;da?(b.onload=function(){e=!0;c()},b.onerror=function(){e=!0;g=Error("Stylesheet failed to load");c()}):setTimeout(function(){e=!0;c()},0);u(a,"head",b)}function B(a,b,d,c){var e=a.c.getElementsByTagName("head")[0];if(e){var f=t(a,"script",{src:b}),g=!1;f.onload=f.onreadystatechange=function(){g||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState||(g=!0,d&&d(null),f.onload=f.onreadystatechange=null,"HEAD"==f.parentNode.tagName&&e.removeChild(f))};e.appendChild(f);setTimeout(function(){g||(g=!0,d&&d(Error("Script load timeout")))},c||5E3);return f}return null};function C(){this.a=0;this.c=null}function D(a){a.a++;return function(){a.a--;E(a)}}function F(a,b){a.c=b;E(a)}function E(a){0==a.a&&a.c&&(a.c(),a.c=null)};function G(a){this.a=a||"-"}G.prototype.c=function(a){for(var b=[],d=0;d<arguments.length;d++)b.push(arguments[d].replace(/[\W_]+/g,"").toLowerCase());return b.join(this.a)};function H(a,b){this.c=a;this.f=4;this.a="n";var d=(b||"n4").match(/^([nio])([1-9])$/i);d&&(this.a=d[1],this.f=parseInt(d[2],10))}function fa(a){return I(a)+" "+(a.f+"00")+" 300px "+J(a.c)}function J(a){var b=[];a=a.split(/,\s*/);for(var d=0;d<a.length;d++){var c=a[d].replace(/['"]/g,"");-1!=c.indexOf(" ")||/^\d/.test(c)?b.push("'"+c+"'"):b.push(c)}return b.join(",")}function K(a){return a.a+a.f}function I(a){var b="normal";"o"===a.a?b="oblique":"i"===a.a&&(b="italic");return b}function ga(a){var b=4,d="n",c=null;a&&((c=a.match(/(normal|oblique|italic)/i))&&c[1]&&(d=c[1].substr(0,1).toLowerCase()),(c=a.match(/([1-9]00|normal|bold)/i))&&c[1]&&(/bold/i.test(c[1])?b=7:/[1-9]00/.test(c[1])&&(b=parseInt(c[1].substr(0,1),10))));return d+b};function ha(a,b){this.c=a;this.f=a.m.document.documentElement;this.h=b;this.a=new G("-");this.j=!1!==b.events;this.g=!1!==b.classes}function ia(a){a.g&&w(a.f,[a.a.c("wf","loading")]);L(a,"loading")}function M(a){if(a.g){var b=y(a.f,a.a.c("wf","active")),d=[],c=[a.a.c("wf","loading")];b||d.push(a.a.c("wf","inactive"));w(a.f,d,c)}L(a,"inactive")}function L(a,b,d){if(a.j&&a.h[b])if(d)a.h[b](d.c,K(d));else a.h[b]()};function ja(){this.c={}}function ka(a,b,d){var c=[],e;for(e in b)if(b.hasOwnProperty(e)){var f=a.c[e];f&&c.push(f(b[e],d))}return c};function N(a,b){this.c=a;this.f=b;this.a=t(this.c,"span",{"aria-hidden":"true"},this.f)}function O(a){u(a.c,"body",a.a)}function P(a){return"display:block;position:absolute;top:-9999px;left:-9999px;font-size:300px;width:auto;height:auto;line-height:normal;margin:0;padding:0;font-variant:normal;white-space:nowrap;font-family:"+J(a.c)+";"+("font-style:"+I(a)+";font-weight:"+(a.f+"00")+";")};function Q(a,b,d,c,e,f){this.g=a;this.j=b;this.a=c;this.c=d;this.f=e||3E3;this.h=f||void 0}Q.prototype.start=function(){var a=this.c.m.document,b=this,d=q(),c=new Promise(function(c,e){function k(){q()-d>=b.f?e():a.fonts.load(fa(b.a),b.h).then(function(a){1<=a.length?c():setTimeout(k,25)},function(){e()})}k()}),e=new Promise(function(a,c){setTimeout(c,b.f)});Promise.race([e,c]).then(function(){b.g(b.a)},function(){b.j(b.a)})};function R(a,b,d,c,e,f,g){this.v=a;this.B=b;this.c=d;this.a=c;this.s=g||"BESbswy";this.f={};this.w=e||3E3;this.u=f||null;this.o=this.j=this.h=this.g=null;this.g=new N(this.c,this.s);this.h=new N(this.c,this.s);this.j=new N(this.c,this.s);this.o=new N(this.c,this.s);a=new H(this.a.c+",serif",K(this.a));a=P(a);this.g.a.style.cssText=a;a=new H(this.a.c+",sans-serif",K(this.a));a=P(a);this.h.a.style.cssText=a;a=new H("serif",K(this.a));a=P(a);this.j.a.style.cssText=a;a=new H("sans-serif",K(this.a));a=P(a);this.o.a.style.cssText=a;O(this.g);O(this.h);O(this.j);O(this.o)}var S={D:"serif",C:"sans-serif"},T=null;function U(){if(null===T){var a=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent);T=!!a&&(536>parseInt(a[1],10)||536===parseInt(a[1],10)&&11>=parseInt(a[2],10))}return T}R.prototype.start=function(){this.f.serif=this.j.a.offsetWidth;this.f["sans-serif"]=this.o.a.offsetWidth;this.A=q();la(this)};function ma(a,b,d){for(var c in S)if(S.hasOwnProperty(c)&&b===a.f[S[c]]&&d===a.f[S[c]])return!0;return!1}function la(a){var b=a.g.a.offsetWidth,d=a.h.a.offsetWidth,c;(c=b===a.f.serif&&d===a.f["sans-serif"])||(c=U()&&ma(a,b,d));c?q()-a.A>=a.w?U()&&ma(a,b,d)&&(null===a.u||a.u.hasOwnProperty(a.a.c))?V(a,a.v):V(a,a.B):na(a):V(a,a.v)}function na(a){setTimeout(p(function(){la(this)},a),50)}function V(a,b){setTimeout(p(function(){v(this.g.a);v(this.h.a);v(this.j.a);v(this.o.a);b(this.a)},a),0)};function W(a,b,d){this.c=a;this.a=b;this.f=0;this.o=this.j=!1;this.s=d}var X=null;W.prototype.g=function(a){var b=this.a;b.g&&w(b.f,[b.a.c("wf",a.c,K(a).toString(),"active")],[b.a.c("wf",a.c,K(a).toString(),"loading"),b.a.c("wf",a.c,K(a).toString(),"inactive")]);L(b,"fontactive",a);this.o=!0;oa(this)};W.prototype.h=function(a){var b=this.a;if(b.g){var d=y(b.f,b.a.c("wf",a.c,K(a).toString(),"active")),c=[],e=[b.a.c("wf",a.c,K(a).toString(),"loading")];d||c.push(b.a.c("wf",a.c,K(a).toString(),"inactive"));w(b.f,c,e)}L(b,"fontinactive",a);oa(this)};function oa(a){0==--a.f&&a.j&&(a.o?(a=a.a,a.g&&w(a.f,[a.a.c("wf","active")],[a.a.c("wf","loading"),a.a.c("wf","inactive")]),L(a,"active")):M(a.a))};function pa(a){this.j=a;this.a=new ja;this.h=0;this.f=this.g=!0}pa.prototype.load=function(a){this.c=new ca(this.j,a.context||this.j);this.g=!1!==a.events;this.f=!1!==a.classes;qa(this,new ha(this.c,a),a)};function ra(a,b,d,c,e){var f=0==--a.h;(a.f||a.g)&&setTimeout(function(){var a=e||null,k=c||null||{};if(0===d.length&&f)M(b.a);else{b.f+=d.length;f&&(b.j=f);var h,m=[];for(h=0;h<d.length;h++){var l=d[h],n=k[l.c],r=b.a,x=l;r.g&&w(r.f,[r.a.c("wf",x.c,K(x).toString(),"loading")]);L(r,"fontloading",x);r=null;null===X&&(X=window.FontFace?(x=/Gecko.*Firefox\/(\d+)/.exec(window.navigator.userAgent))?42<parseInt(x[1],10):!0:!1);X?r=new Q(p(b.g,b),p(b.h,b),b.c,l,b.s,n):r=new R(p(b.g,b),p(b.h,b),b.c,l,b.s,a,n);m.push(r)}for(h=0;h<m.length;h++)m[h].start()}},0)}function qa(a,b,d){var c=[],e=d.timeout;ia(b);var c=ka(a.a,d,a.c),f=new W(a.c,b,e);a.h=c.length;b=0;for(d=c.length;b<d;b++)c[b].load(function(b,c,d){ra(a,f,b,c,d)})};function sa(a,b){this.c=a;this.a=b}function ta(a,b,d){var c=z(a.c);a=(a.a.api||"fast.fonts.net/jsapi").replace(/^.*http(s?):(\/\/)?/,"");return c+"//"+a+"/"+b+".js"+(d?"?v="+d:"")}sa.prototype.load=function(a){function b(){if(e["__mti_fntLst"+d]){var c=e["__mti_fntLst"+d](),g=[],k;if(c)for(var h=0;h<c.length;h++){var m=c[h].fontfamily;void 0!=c[h].fontStyle&&void 0!=c[h].fontWeight?(k=c[h].fontStyle+c[h].fontWeight,g.push(new H(m,k))):g.push(new H(m))}a(g)}else setTimeout(function(){b()},50)}var d=this.a.projectId,c=this.a.version;if(d){var e=this.c.m;B(this.c,ta(this,d,c),function(c){c?a([]):b()}).id="__MonotypeAPIScript__"+d}else a([])};function ua(a,b){this.c=a;this.a=b}ua.prototype.load=function(a){var b,d,c=this.a.urls||[],e=this.a.families||[],f=this.a.testStrings||{},g=new C;b=0;for(d=c.length;b<d;b++)A(this.c,c[b],D(g));var k=[];b=0;for(d=e.length;b<d;b++)if(c=e[b].split(":"),c[1])for(var h=c[1].split(","),m=0;m<h.length;m+=1)k.push(new H(c[0],h[m]));else k.push(new H(c[0]));F(g,function(){a(k,f)})};function va(a,b,d){a?this.c=a:this.c=b+wa;this.a=[];this.f=[];this.g=d||""}var wa="//fonts.googleapis.com/css";function xa(a,b){for(var d=b.length,c=0;c<d;c++){var e=b[c].split(":");3==e.length&&a.f.push(e.pop());var f="";2==e.length&&""!=e[1]&&(f=":");a.a.push(e.join(f))}}function ya(a){if(0==a.a.length)throw Error("No fonts to load!");if(-1!=a.c.indexOf("kit="))return a.c;for(var b=a.a.length,d=[],c=0;c<b;c++)d.push(a.a[c].replace(/ /g,"+"));b=a.c+"?family="+d.join("%7C");0<a.f.length&&(b+="&subset="+a.f.join(","));0<a.g.length&&(b+="&text="+encodeURIComponent(a.g));return b};function za(a){this.f=a;this.a=[];this.c={}}var Aa={latin:"BESbswy",cyrillic:"\u0439\u044f\u0416",greek:"\u03b1\u03b2\u03a3",khmer:"\u1780\u1781\u1782",Hanuman:"\u1780\u1781\u1782"},Ba={thin:"1",extralight:"2","extra-light":"2",ultralight:"2","ultra-light":"2",light:"3",regular:"4",book:"4",medium:"5","semi-bold":"6",semibold:"6","demi-bold":"6",demibold:"6",bold:"7","extra-bold":"8",extrabold:"8","ultra-bold":"8",ultrabold:"8",black:"9",heavy:"9",l:"3",r:"4",b:"7"},Ca={i:"i",italic:"i",n:"n",normal:"n"},Da=/^(thin|(?:(?:extra|ultra)-?)?light|regular|book|medium|(?:(?:semi|demi|extra|ultra)-?)?bold|black|heavy|l|r|b|[1-9]00)?(n|i|normal|italic)?$/;function Ea(a){for(var b=a.f.length,d=0;d<b;d++){var c=a.f[d].split(":"),e=c[0].replace(/\+/g," "),f=["n4"];if(2<=c.length){var g;var k=c[1];g=[];if(k)for(var k=k.split(","),h=k.length,m=0;m<h;m++){var l;l=k[m];if(l.match(/^[\w-]+$/)){var n=Da.exec(l.toLowerCase());if(null==n)l="";else{l=n[2];l=null==l||""==l?"n":Ca[l];n=n[1];if(null==n||""==n)n="4";else var r=Ba[n],n=r?r:isNaN(n)?"4":n.substr(0,1);l=[l,n].join("")}}else l="";l&&g.push(l)}0<g.length&&(f=g);3==c.length&&(c=c[2],g=[],c=c?c.split(","):g,0<c.length&&(c=Aa[c[0]])&&(a.c[e]=c))}a.c[e]||(c=Aa[e])&&(a.c[e]=c);for(c=0;c<f.length;c+=1)a.a.push(new H(e,f[c]))}};function Fa(a,b){this.c=a;this.a=b}var Ga={Arimo:!0,Cousine:!0,Tinos:!0};Fa.prototype.load=function(a){var b=new C,d=this.c,c=new va(this.a.api,z(d),this.a.text),e=this.a.families;xa(c,e);var f=new za(e);Ea(f);A(d,ya(c),D(b));F(b,function(){a(f.a,f.c,Ga)})};function Ha(a,b){this.c=a;this.a=b}Ha.prototype.load=function(a){var b=this.a.id,d=this.c.m;b?B(this.c,(this.a.api||"https://use.typekit.net")+"/"+b+".js",function(b){if(b)a([]);else if(d.Typekit&&d.Typekit.config&&d.Typekit.config.fn){b=d.Typekit.config.fn;for(var e=[],f=0;f<b.length;f+=2)for(var g=b[f],k=b[f+1],h=0;h<k.length;h++)e.push(new H(g,k[h]));try{d.Typekit.load({events:!1,classes:!1,async:!0})}catch(m){}a(e)}},2E3):a([])};function Ia(a,b){this.c=a;this.f=b;this.a=[]}Ia.prototype.load=function(a){var b=this.f.id,d=this.c.m,c=this;b?(d.__webfontfontdeckmodule__||(d.__webfontfontdeckmodule__={}),d.__webfontfontdeckmodule__[b]=function(b,d){for(var g=0,k=d.fonts.length;g<k;++g){var h=d.fonts[g];c.a.push(new H(h.name,ga("font-weight:"+h.weight+";font-style:"+h.style)))}a(c.a)},B(this.c,z(this.c)+(this.f.api||"//f.fontdeck.com/s/css/js/")+ea(this.c)+"/"+b+".js",function(b){b&&a([])})):a([])};var Y=new pa(window);Y.a.c.custom=function(a,b){return new ua(b,a)};Y.a.c.fontdeck=function(a,b){return new Ia(b,a)};Y.a.c.monotype=function(a,b){return new sa(b,a)};Y.a.c.typekit=function(a,b){return new Ha(b,a)};Y.a.c.google=function(a,b){return new Fa(b,a)};var Z={load:p(Y.load,Y)};"function"===typeof define&&define.amd?define(function(){return Z}):"undefined"!==typeof module&&module.exports?module.exports=Z:(window.WebFont=Z,window.WebFontConfig&&Y.load(window.WebFontConfig));}());
/* jshint ignore:end */

(function($) {
				
	"use strict";
	
	// ======================================================
	// Helper functions
	// ======================================================
	
	// elements main var
	var tg_font_arrays = [],
		tg_element_name = '',
		tg_element_id = 1,
		elements_content = $('.tg-panel-element').data('elements-content'),
		tg_anim = $('[data-element-animations]').data('element-animations');
		
	$('.tg-panel-element').removeAttr('data-elements-content');
	$('[data-element-animations]').removeAttr('data-element-animations');
	
	var unvalid_rules = [
		'positions-unit',
		'z-index',
		'float',
		'width',
		'height',
		'width-unit',
		'height-unit',
		'margin-unit',
		'padding-unit',
		'border-unit',
		'border-radius-unit',
		'box-shadow-unit',
		'box-shadow-color',
		'box-shadow-horizontal',
		'box-shadow-vertical',
		'box-shadow-blur',
		'box-shadow-size',
		'box-shadow-inset-unit',
		'box-shadow-inset-color',
		'box-shadow-inset-horizontal',
		'box-shadow-inset-vertical',
		'box-shadow-inset-blur',
		'box-shadow-inset-size',
		'box-shadow-inset-inset',
		'text-shadow-unit',
		'text-shadow-color',
		'text-shadow-horizontal',
		'text-shadow-vertical',
		'text-shadow-blur',
		'letter-spacing-unit',
		'word-spacing-unit',
		'background-position-x-unit',
		'background-position-y-unit',
		'positions-from',
		'top','bottom','left','right',
		'line-height-unit',
		'font-size-unit',
		'background-image',
		'position',
		'display',
		'overflow',
		'opacity',
		'visibility',
		'custom-rules',
		'margin-unit',
		'margin-top',
		'margin-right',
		'margin-bottom',
		'margin-left',
		'padding-unit',
		'padding-top',
		'padding-right',
		'padding-bottom',
		'padding-left',
		'border-unit',
		'border-top',
		'border-right',
		'border-bottom',
		'border-left',
		'border-radius-unit',
		'border-top-left-radius',
		'border-top-right-radius',
		'border-bottom-right-radius',
		'border-bottom-left-radius',
		'font-subset',
		'font-family',
	];
	
	var timing_functions = {
		'ease'           : 'ease',
		'linear'         : 'linear',
		'ease-in'        : 'ease-in',
		'ease-out'       : 'ease-out',
		'ease-in-out'    : 'ease-in-out',
		'easeInCubic'    : 'cubic-bezier(0.550, 0.055, 0.675, 0.190)',
		'easeOutCubic'   : 'cubic-bezier(0.215, 0.610, 0.355, 1.000)',
		'easeInOutCubic' : 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
		'easeInCirc'     : 'cubic-bezier(0.600, 0.040, 0.980, 0.335)',
		'easeOutCirc'    : 'cubic-bezier(0.075, 0.820, 0.165, 1.000)',
		'easeInOutCirc'  : 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
		'easeInExpo'     : 'cubic-bezier(0.950, 0.050, 0.795, 0.035)',
		'easeOutExpo'    : 'cubic-bezier(0.190, 1.000, 0.220, 1.000)',
		'easeInOutExpo'  : 'cubic-bezier(1.000, 0.000, 0.000, 1.000)',
		'easeInQuad'     : 'cubic-bezier(0.550, 0.085, 0.680, 0.530)',
		'easeOutQuad'    : 'cubic-bezier(0.250, 0.460, 0.450, 0.940)',
		'easeInOutQuad'  : 'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
		'easeInQuart'    : 'cubic-bezier(0.895, 0.030, 0.685, 0.220)',
		'easeOutQuart'   : 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
		'easeInOutQuart' : 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
		'easeInQuint'    : 'cubic-bezier(0.755, 0.050, 0.855, 0.060)',
		'easeOutQuint'   : 'cubic-bezier(0.230, 1.000, 0.320, 1.000)',
		'easeInOutQuint' : 'cubic-bezier(0.860, 0.000, 0.070, 1.000)',
		'easeInSine'     : 'cubic-bezier(0.470, 0.000, 0.745, 0.715)',
		'easeOutSine'    : 'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
		'easeInOutSine'  : 'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
		'easeInBack'     : 'cubic-bezier(0.600, -0.280, 0.735, 0.045)',
		'easeOutBack'    : 'cubic-bezier(0.175,  0.885, 0.320, 1.275)',
		'easeInOutBack'  : 'cubic-bezier(0.680, -0.550, 0.265, 1.550)'
	};
		
	//Check element in droppable area
	function check_dropped_element() {
		
		$('.tg-item-inner [data-item-area]').removeClass('tg-area-filled').each(function() {
			
			var $this  = $(this);
			
			if ($this.find('.tg-element-draggable').length > 0) {
				$this.addClass('tg-area-filled');
			}
			
		});
			
	}
	
	// Check element in droppable area on start (if element is alone)
	function check_dropped_element_start(el) {
		
		var $elems = el.closest('[data-item-area]').find('.tg-element-draggable');
		if ($elems.length === 1) {
			el.closest('[data-item-area]').removeClass('tg-area-filled');
		}
		
	}
	
	// auto update element name in the layout
	function update_element_name(el) {
		
		$('.tg-element-draggable').removeClass('tg-element-selected');
		el.addClass('tg-element-selected');		
		tg_element_name = el.data('name');	
		$('.tg-panel-element .tg-container-title span').text(' ('+tg_element_name+')');
		$('.tg-panel-element .idle_state, .tg-panel-element .hover_state').data('element', tg_element_name);
		update_element_list();
				
	}
	
	// Generate unique element ID
	function generate_unique_id() {
		
		var existing_el = [];
		
		$('.tg-item-inner .tg-element-init').each(function() {
			existing_el.push(this.className.match(/tg-element-(\d+)/)[1]);
		});
		
		existing_el.sort(function sortNumber(a,b) {return a - b;});
		existing_el[existing_el.length+1] = '';

		for (var i = 0; i < existing_el.length; i++) {
			if (existing_el[i] != i+1) {
            	tg_element_id = i+1;
				break;
			}
		}
		
		tg_element_id = (tg_element_id == existing_el.length ) ? tg_element_id+1 : tg_element_id;
		
		return tg_element_id;
	
	}
	
	
	// update element dropdown list
	var $element_list = $('.tg-element-class select'),
		tg_no_element = $element_list.find('option:first-child').text();
		
	function update_element_list() {
			
		var selected_el = $('.tg-skin-build-inner .tg-element-draggable.tg-element-selected').length;
			
		$element_list.html('');
	
		$('.tg-skin-build-inner .tg-element-draggable').each(function() {
			
			var name,
				value        = $(this).data('name'),
				settings     = $(this).data('settings');
				
			if (settings) {	
			
				var source_type   = settings.source.source_type,
					post_content  = settings.source.post_content,
					video_content = settings.source.video_stream_content,
					woo_content   = settings.source.woocommerce_content;
	
				if (source_type === 'post') {
					name = $('select[name="post_content"] option[value="'+post_content+'"]').text();
				} else if (source_type === 'media_button' || source_type === 'social_link' ||source_type === 'icon' || source_type === 'html' || source_type === 'line_break') {
					name = $('select[name="source_type"] option[value="'+source_type+'"]').text();
				} else if (source_type === 'video_stream') {
					name = $('select[name="video_stream_content"] option[value="'+video_content+'"]').text();
				} else if (source_type === 'woocommerce') {
					name = $('select[name="woocommerce_content"] option[value="'+woo_content+'"]').text();
				}
				
				$element_list.append('<option value="'+value+'">'+name+' ('+value+')</option>');
			}

		});
		
		$element_list.val(tg_element_name);
		update_select($('.tg-element-class'));
		
		if (selected_el === 0) {
			$('.tg-element-class .tomb-select-value').text(tg_no_element);
		}
		
	}
	
	
	// change select on element click
	function update_select(el) {
			
		el.find('.tomb-select-holder').each(function() {
				
			var $this = $(this),
				value = $this.find('select option:selected').text();
				
			$this.find('.tomb-select-value').text(value);
				
			if (value) {
				$this.find('.tomb-select-placeholder').hide();
				$this.find('.tomb-select-clear').show();
			} else {
				$this.find('.tomb-select-placeholder').show();
				$this.find('.tomb-select-clear').hide();
			}
				
		});
			
	}
		
	// change input colors on element click
	function update_colors(el) {
		
		el.find('.tomb-colorpicker').each(function() {

			$(this).wpColorPicker('color', $(this).val());
			if (!$(this).val()) {
				$(this).closest('.tomb-row').find('.wp-color-result').attr('style', '');
			}
			
		});
			
	}
	
	// change element color on input change
	function update_element_color(el) {
			
		if (!el.closest('[data-element]').data('element')) {
			save_element_settings();		
		}
		if (el.closest('[data-settings="source"]').length) {
			format_element();
		}
		
		style_change(el);
		
	}
	
	// change sliders on element click
	function update_sliders(el) {
		
		el.find('.tomb-slider-range').each(function() {
			
			var value = $(this).closest('.tomb-type-slider').find('.tomb-slider-input').val();
			$(this).slider('value', value);
				
		});
			
	}
	
	var tg_fonts = $.makeArray(tg_skin_fonts);
	
	function update_font_weight(font_family, font_weight, font_subset) {

		var subsets,
			variants;

		if (typeof font_family !== 'undefined' && tg_fonts[0].hasOwnProperty(font_family)) {
			variants = tg_fonts[0][font_family].variants;
			subsets = tg_fonts[0][font_family].subsets;
		} else {
			variants = ['100', '200', '300', '400', '500', '600', '700', '800', '900'];
			subsets = [''];
		}

		var variant_options = '';
		for (var i = 0; i < variants.length; i++) {
			if (Math.floor(variants[i]) == variants[i] && $.isNumeric(variants[i])) {
				variant_options += '<option value="'+variants[i]+'">'+variants[i]+'</option>';
			}
		}
		
		var subset_options = '';
		for (i = 0; i < subsets.length; i++) {
			if (subsets[i]) {
				subset_options += '<option value="'+subsets[i]+'">'+subsets[i]+'</option>';
			}
		}

		font_weight = ($.inArray(font_weight, variants) > -1) ? font_weight : variants[0];
		font_subset = ($.inArray(font_subset, subsets) > -1) ? font_subset : subsets[0];
		
		$('.element_idle_font-weight .tomb-select-value').text(font_weight);
		$('.element_idle_font-subset .tomb-select-value').text(font_subset);
		$('[name="element_idle_font-weight"]').html(variant_options).val(font_weight);
		$('[name="element_idle_font-subset"]').html(subset_options).val(font_subset);
	
	}
		
	// change image field on element click
	function update_image(el) {
			
		el.find('.tomb-type-image').each(function() {
				
			var $this = $(this),
				value = $this.find('input').val();
					
			if (value) {
				$this.find('.tomb-img-field').css('background-image','url('+value+')').show();
				$this.find('.tomb-image-remove').css('display','inline-block');
			} else {
				$this.find('.tomb-img-field').hide();
				$this.find('.tomb-image-remove').hide();
			}
				
		});
			
	}
	
	// check item content position	
	function check_content_position() {
			
		$('.tg-item-content-holder, [data-target="tg-tab-top-content-styles"], [data-target="tg-tab-bottom-content-styles"]').hide();
		var position = $('select[name="content_position"]').val();
			
		switch (position) {
			case 'both':
        		$('.tg-item-content-holder[data-position="top"], [data-target="tg-tab-top-content-styles"]').show();
				$('.tg-item-content-holder[data-position="bottom"], [data-target="tg-tab-bottom-content-styles"]').show();
        		break;
    		case 'top':
        		$('.tg-item-content-holder[data-position="top"], [data-target="tg-tab-top-content-styles"]').show();
        		break;
			case 'bottom':
				$('.tg-item-content-holder[data-position="bottom"], [data-target="tg-tab-bottom-content-styles"]').show();
				break;
		}
	
	}
	
	// check item style	
	function check_skin_style() {
			
		var skin_style = $('select[name="skin_style"]').val(),
			media_content = $('input[name="media_content"]').is(':checked'),
			$content_none = $('[name="content_position"]').find('option[value="none"]');
		
		$('.tg-skin-build-inner').removeClass('tg-grid-style tg-masonry-style');
		
		if (skin_style === 'grid') {
			
			$('.tg-skin-build-inner').addClass('tg-grid-style');
			$('.tg-item-content-holder').hide();
			$('[data-target="tg-tab-layer-depths"]').hide();
			$('[data-target="tg-tab-media-styles"], [data-target="tg-tab-overlay-styles"], [data-target="tg-tab-media-content-styles"], [data-target="tg-tab-media-animations"]').show();
			$('[data-target="tg-tab-top-content-styles"], [data-target="tg-tab-bottom-content-styles"]').hide();
		} else {
			check_content_position();
			$('[data-target="tg-tab-media-styles"], [data-target="tg-tab-overlay-styles"]').show();
			$('[data-target="tg-tab-layer-depths"]').show();
		}
		
		if (skin_style === 'masonry' && !media_content) {
			$('.tg-skin-build-inner').addClass('tg-masonry-style');
			$content_none.attr('disabled', true);
			$('[data-target="tg-tab-media-styles"], [data-target="tg-tab-media-holder-styles"], [data-target="tg-tab-overlay-styles"], [data-target="tg-tab-media-content-styles"], [data-target="tg-tab-media-animations"]').hide();
			$('.tg-item-media-holder').hide();
			$('.tomb-row.overlay_type').hide();
		} else {
			$content_none.removeAttr('disabled');
			$('[data-target="tg-media-styles"], [data-target="tg-tab-media-holder-styles"], [data-target="tg-tab-overlay-styles"], [data-target="tg-tab-media-content-styles"], [data-target="tg-tab-media-animations"]').show();
			$('.tg-item-media-holder').show();
			$('.tomb-row.overlay_type').show();
		}
		
	}
		
	// check item layout size	
	function check_item_size() {
		
		var skin_style = $('select[name="skin_style"]').val(),
			ratio_X = (skin_style === 'grid') ? $('[name="item_x_ratio"]').val() : 4,
			ratio_Y = (skin_style === 'grid') ? $('[name="item_y_ratio"]').val() : 3,
			col_nb  = $('[name="skin_col"]').val(),
			row_nb  = (skin_style === 'grid') ? $('[name="skin_row"]').val() : 1,
			item_w  = $('.tg-item-inner').data('width');

		$('.tg-skin-build-inner').attr('data-col',col_nb);
		$('.tg-item-inner').width(col_nb*item_w);
		$('.tg-item-media-holder').height(row_nb*item_w*ratio_Y/ratio_X);
	
	}
	
	// Apply layer depth	
	function apply_layer_depths() {
			
		var top_zindex    = $('[name="top_content_idle_z-index"]').val(),
			media_zindex  = $('[name="media_holder_idle_z-index"]').val(),
			bottom_zindex = $('[name="bottom_content_idle_z-index"]').val();
			
		if ($('select[name="skin_style"]').val() === 'grid') {
			top_zindex    = 0;
			bottom_zindex = 0;
			media_zindex  = 0;
		}
		
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="top"]').css('z-index', (!top_zindex) ? '' : top_zindex);
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="bottom"]').css('z-index', (!bottom_zindex) ? '' : bottom_zindex);
		$('.tg-skin-build-inner .tg-item-media-holder').css('z-index', (!media_zindex) ? '' : media_zindex);
		
		top_zindex    = top_zindex - 1;
		bottom_zindex = bottom_zindex - 1;
		media_zindex  = media_zindex - 1;
			
		top_zindex    = (top_zindex < 0 || !top_zindex) ? 0 : top_zindex;
		bottom_zindex = (bottom_zindex < 0 || !bottom_zindex) ? 0 : bottom_zindex;
		media_zindex  = (media_zindex < 0 || !media_zindex) ? 0 : media_zindex;
		
		if (top_zindex === media_zindex && top_zindex === bottom_zindex && media_zindex === bottom_zindex) {
			top_zindex = media_zindex = bottom_zindex = 1;
		}
				
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="top"]').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').addClass('z-index-'+top_zindex);
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="bottom"]').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').addClass('z-index-'+bottom_zindex);
		$('.tg-skin-build-inner .tg-item-media-holder').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').addClass('z-index-'+media_zindex);
			
	}
	
	// Apply layer depth	
	function disable_layer_depths() {
	
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="top"]').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').css('z-index', '');
		$('.tg-skin-build-inner .tg-item-content-holder[data-position="bottom"]').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').css('z-index', '');
		$('.tg-skin-build-inner .tg-item-media-holder').removeClass('z-index-0 z-index-1 z-index-2 z-index-3').css('z-index', '');
	
	}
	
	var $tg_overlay = $('.tg-item-overlay').clone();
	
	// check overlay type
	function check_overlay_type() {
		
		var overlay_type = $('select[name="overlay_type"]').val();
			
		$('.tg-item .tg-item-overlay').remove();
		
		if (overlay_type === 'full') {
			$('.tg-overlay-positions').hide();
			$('.tg-overlay-positions [data-target="tg-tab-overlay-center"]').trigger('click');
			$('.tg-item-media-content').prepend($tg_overlay.attr('data-position', 'center'));
		} else if (overlay_type === 'content') {
			$('.tg-overlay-positions').show();
			$('.tg-overlay-positions [data-target="tg-tab-overlay-top"]').trigger('click');
			$('.tg-item-overlay-content').prepend($tg_overlay);
			$('.tg-item-overlay').each(function(index, element) {
                $(this).attr('data-position', $(this).closest('.tg-item-overlay-content').data('position'));
            });
		}
	
	}

	// search indexof array in array
	function containsAll(needles, haystack){ 
	
		for(var i = 0 , len1 = haystack.length; i < len1; i++){
			for(var y = 0 , len2 = needles.length; y < len2; y++){
				if (haystack[i].indexOf(needles[y]) >= 0) return true;
			}
		}
		return false;
		
	}
	
	// get css direction (top, left, bottom, right)
	function get_css_directions(val1, val2, val3, val4, unit, imp, arr, nb) {
		
		if (arr) {
			
			imp  = check_value(imp, arr) ? ' !important' : '';
			unit = check_value(unit, arr, 'px');
			val1 = check_value(val1, arr);
			val2 = check_value(val2, arr);
			val3 = check_value(val3, arr);
			val4 = check_value(val4, arr);
				
			if ($.isNumeric(val1) || $.isNumeric(val2) || $.isNumeric(val3) || $.isNumeric(val4)) {
				val1  = (Number(val1) !== 0) ? val1+unit : '0';
				val2  = (Number(val2) !== 0) ? val2+unit : '0';
				val3  = (Number(val3) !== 0) ? val3+unit : '0';
				val4  = (Number(val4) !== 0) ? val4+unit : '0';
				return (nb != 3) ? val1+' '+val2+' '+val3+' '+val4+imp : val1+' '+val2+' '+val3+imp;
			}
			
		}
		
		return '';
		
	}
	
	// check css array value
	function check_value(val, arr, def) {
		
		def = def || '';
		return (arr[val] !== undefined && arr[val]) ? arr[val] : def;
	
	}
	
	// Sanitize css
	function sanitize_CSS(input) {
	
		var output = input.replace(/<script[^>]*?>.*?<\/script>/gi, '').
						replace(/<[\/\!]*?[^<>]*?>/gi, '').
						replace(/<style[^>]*?>.*?<\/style>/gi, '').
						replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '');
						
		return output;
			
	}
	
	// Sanitize html
	function sanitize_HTML(input) {
	
		var output = input.replace(/<script[^>]*?>.*?<\/script>/gi, '').
						replace(/<script[^>]*?>/gi, '').
						replace(/<\/script[^>]*?>/gi, '').
						replace(/<style[^>]*?>/gi, '').
						replace(/<\/style[^>]*?>/gi, '').
						replace(/<style[^>]*?>.*?<\/style>/gi, '');
						
		return output;
			
	}
	
	// Sanitize string
	function sanitize_string(string) {
		
		if (string) {
			
			return string.toLowerCase()
				.replace(/ /g, '-')
				.replace( /|%[a-fA-F0-9][a-fA-F0-9]|/g, '')
				.replace( /[^A-Za-z0-9_-]/g, '')
				.replace(/-+/g,'-')
				.replace(/\-$/, '');
				
		}
		
	}
	
	// update icon field
	function update_icon_field() {
		
		$('.tg-icon-holder').each(function() {
			var icon = $(this).find('input').val();
			$(this).find('i').attr('class','');
			$(this).find('i').addClass(icon);
		});
		
	}
	// debounce event (reduce number of calculation for smooth interface)
	function tg_debounce(fn, delay) {
		
		var timer = null;
		
		return function () {
			
			var context = this, args = arguments;
			
			clearTimeout(timer);
			
			timer = setTimeout(function () {
				fn.apply(context, args);
			}, delay);
			
		};
		
	}
	
	// get computed style of an element
	function get_element_size(el) {
			
		var bound = el[0].getBoundingClientRect(),
			mg_t  = parseFloat(el.css('marginTop')),
			mg_b  = parseFloat(el.css('marginBottom')),
			mg_l  = parseFloat(el.css('marginLeft')),
			mg_r  = parseFloat(el.css('marginRight')),
			pd_t  = parseFloat(el.css('paddingTop')),
			pd_b  = parseFloat(el.css('paddingBottom')),
			pd_l  = parseFloat(el.css('paddingLeft')),
			pd_r  = parseFloat(el.css('paddingRight'));
			
		var mg_ti  = (mg_t > 0) ? mg_t : null,
			mg_bi  = (mg_b > 0) ? mg_b : null,
			mg_li  = (mg_l > 0) ? mg_l : null,
			mg_ri  = (mg_r > 0) ? mg_r : null;
		
		var size = {
				width       : bound.width,
				height      : bound.height,
				innerWidth  : bound.width - pd_l - pd_r,
				innerHeight : bound.height - pd_t - pd_b,
				outerWidth  : bound.width + mg_li + mg_ri,
				outerHeight : bound.height + mg_ti + mg_bi,
				padding : {
					top    : pd_t,
					right  : pd_r,
					bottom : pd_b,
					left   : pd_l
				},
				margin : {
					top    : mg_t,
					right  : mg_r,
					bottom : mg_b,
					left   : mg_l
				}
			};
			
		return size;
		
	}
	
	// show message if skin was modified and not sve before leaving the page
	var skin_was_modified = false;
	window.onbeforeunload = function (e) {
		
		if (skin_was_modified) {
			
			var message = elements_content.skin_was_modified;
			e = e || window.event;

			if (e) {
				e.returnValue = message;
			}

			return message;
			
		}
		
	};
	
	// reset prevention on before load if skin saved
	$('#tg_skin_save').on('click', function() {
		skin_was_modified = false;
	});
	
	// remove message on before load if skin download
	$('#tg_download_skin').on('click', function() {
		var before_download = skin_was_modified;
		skin_was_modified = false;
		setTimeout(function(){ 
			skin_was_modified = before_download;
		}, 150);
	});

	// ======================================================
	// Add important field for css
	// ======================================================
	
	// Add important option for each css rules
	$('.tg-component-styles .tomb-row').each(function() {
		
		var name,
			prefix  = $(this).closest('[data-prefix]').data('prefix'),
			classes = $(this).attr('class').split(' '),
			title = elements_content.important_string,
			value = [
			'custom-rules',
			'custom_desc',
			'visibility_desc',
			'shadow-color',
			'shadow-inset'
		];
		
		if (!containsAll(value, classes)) {
			for (var i = 0, l = classes.length; i < l; i++) {
				if (classes[i].indexOf(prefix) >= 0) {
					name = classes[i]+'-important';
				}
			}
			$(this).append('<div class="tg-important-rule"><input title="'+title+'" name="'+name+'" type="checkbox"><span></span></div>');
		}
		
    });
	
	// ======================================================
	// Build skin function
	// ======================================================

	// build skin from settings
	function build_skin() {
		
		if (typeof tg_skin_settings !== 'undefined') {
			
			var $input, item, el, tp, st, element,
				settings = $.parseJSON(tg_skin_settings),
				item_settings = (settings) ? settings.item : '',
				elements_settings = (settings) ? settings.elements : '';

			// set item settings
			for (settings in item_settings) {
					
				var prefix = $('.tg-panel-item').find('[data-settings="'+settings+'"]').data('prefix'),
					values = item_settings[settings];
					prefix = (prefix) ? prefix : '';

				if (settings == 'layout') {
					
					for (item in values) {

						$input = $('.tg-panel-item').find('[data-settings="'+settings+'"] [name="'+prefix+item.replace(/(['"])/g, "\\$1")+'"]');
							
						if ($input.is(':checkbox')) {
							$input.prop('checked', values[item]);
						} else {
							$input.val(values[item]);
						}
						
					}
					
				} else if (settings == 'containers') {
					
					for (element in values) {
						
						for (var type in values[element]) {
							
							if (type === 'styles') {
								
								for (var state in values[element][type]) {
									
									el = element.replace(/(['"])/g, "\\$1");
									tp = type.replace(/(['"])/g, "\\$1");
									st = state.replace(/(['"])/g, "\\$1");
									prefix = $('.tg-panel-item').find('[data-settings="'+el+'"] [data-settings="'+tp+'"] [data-settings="'+st+'"]').data('prefix');
									
									if (st === 'is_hover') {
										$('.tg-panel-item').find('[data-settings="'+el+'"] [name="'+st+'"]').prop('checked', values[element][type][state]);
									} else {
										
										for (item in values[element][type][state]) {
											
											if (values[element][type][state].hasOwnProperty(item)) {
											
												$input = $('.tg-panel-item').find('[name="'+prefix+item.replace(/(['"])/g, "\\$1")+'"]');
												if ($input.is(':checkbox')) {
													$input.prop('checked', values[element][type][state][item]);
												} else {
													$input.val(values[element][type][state][item]);
												}
											
											}
											
										}
										
										if ((st == 'hover_state' && values[element][type].is_hover) || st == 'idle_state') {
											style_change(element, values[element][type][state], state);
										}
											
									}

								}
								
							} else if (type === 'animation' || type === 'action') {
								
								el = element.replace(/(['"])/g, "\\$1");
								tp = type.replace(/(['"])/g, "\\$1");
								prefix = $('.tg-panel-item').find('[data-settings="'+tp+'"] [data-settings="'+el+'"]').data('prefix');

								for (item in values[element][type]) {
									$('.tg-panel-item').find('[name="'+prefix+item.replace(/(['"])/g, "\\$1")+'"]').val(values[element][type][item]);
								}
								
							}
						}
					}
					
				} else if (settings == 'global_css') {
					
					$('.tg-panel-item').find('[data-settings="'+settings+'"] [name="'+settings+'"]').val(item_settings[settings]);	
					process_global_css(item_settings[settings]);
					
				}
								
			}
	
			// set elements in layout
			for (settings in elements_settings) {
				
				for (item in elements_settings[settings]) {

					var content = elements_settings[settings][item].content.replace(/\\(.)/mg, "$1");
					element = $('<div class="tg-element-draggable tg-element-init '+item+'" title="'+elements_content.click_to_edit_string+'">'+content+'<div class="tg-element-helper"></div></div>');
					var $area   = $('.tg-skin-build-inner [data-item-area="'+settings+'"]');
					element.data('settings', elements_settings[settings][item]).data('name', item);
					$area.append(element);
					style_change(item, elements_settings[settings][item].styles.idle_state, 'idle_state');
					if (elements_settings[settings][item].styles.is_hover) {
						style_change(item, elements_settings[settings][item].styles.hover_state, 'hover_state');
					}
					// set element color
					TG_element_color('.'+item, elements_settings[settings][item]);
					// load google font for idle & hover state	
					tg_load_font(
						elements_settings[settings][item].styles.idle_state['font-family'],
						elements_settings[settings][item].styles.idle_state['font-weight'],
						elements_settings[settings][item].styles.idle_state['font-subset']
					);
					
				}
				
			}
			
			check_dropped_element();
		
		}
		
	}
	
	// build skin
	build_skin();
	check_content_position();
	check_skin_style();
	check_item_size();
	update_icon_field();

	// ======================================================
	// Generate css styles
	// ======================================================
	
	function style_change(el, settings, has_state) {

		var fields   = (!settings) ? el.closest('[data-settings]') : '',
			element  = (!settings) ? fields.data('element') : el,
			state    = (!settings) ? fields.data('settings') : has_state,
			is_hover = (!settings) ? fields.find('[name="is_hover"]').is(':checked') : true,
			prefix   = (!settings) ? fields.data('prefix') : '',
			pseudo   = (state == 'hover_state') ? ':hover' : '',
			selector = '[data-settings="'+state+'"]',
			str_font = '',
			str_css  = '',
			arr_css  = [];
			
		var font_family = (fields && el.closest('.tomb-row[class^="element_idle_font-family"]').length);

		if (element || settings) {
			
			if (state === 'hover_state' && !is_hover) {
				$('style[class=\''+element+'\']'+selector).remove();
				return false;
			}
			
			if (!settings) {
				
				fields.find('.tomb-row input, .tomb-row select, .tomb-row textarea').each(function() {
					
					var $this  = $(this),
						unit   = $this.closest('.tomb-row').find('.tg-css-unit').val(),
						impor  = ($this.closest('.tomb-row').find('.tg-important-rule input').prop('checked')) ? ' !important' : '',
						hidden = $this.closest('.tomb-row').css('display'),
						value  = (!$this.is(':checkbox')) ? $this.val() : $this.prop('checked'),
						name   = $this.attr('name');
						name   = (name) ? name.replace(prefix,'') : '';

					if (name && value && $.inArray(name,unvalid_rules) < 0 && hidden != 'none' && name.indexOf('important') === -1) {
						unit = (unit) ? unit : '';
						str_css += name+':'+value+unit+impor+';';	
					}
						
					arr_css[name] = value;
					
				});
				
			} else {

				for (var name in settings) {
					
					var unit  = settings[name+'-unit'],
						impor = (settings[name+'-important']) ? ' !important' : '',
						value = settings[name];

					if (name && value && $.inArray(name,unvalid_rules) < 0 && name.indexOf('important') === -1) {
						unit = (unit) ? unit : '';
						str_css += name+':'+value+unit+impor+';';	
					}
						
					arr_css[name] = value;
				}
			
			}
			
			str_css += (state === 'idle_state') ? process_position(arr_css) : '';
			str_css += (state === 'idle_state') ? process_display(arr_css) : '';
			str_css += (state === 'idle_state') ? process_zindex(arr_css) : '';
			str_css += (state === 'idle_state') ? process_float(arr_css) : '';
			str_css += (state === 'idle_state') ? process_positions(arr_css, element) : '';
			str_css += process_visibility(element, state, arr_css);
			str_css += process_sizes(arr_css);
			str_css += process_margin(arr_css);
			str_css += process_padding(arr_css);
			str_css += process_border_width(arr_css);
			str_css += process_border_radius(arr_css);
			str_css += process_box_shadows(arr_css);
			str_css += process_text_shadows(arr_css);
			str_css += process_background_image(arr_css);
			str_css += process_custom_rules(arr_css);
			
			var $styles = $('style[class=\''+element+'\']'+selector),
				target = (state == 'hover_state') ? '.tg-skin-build-inner.tg-item-preview' : '.tg-skin-build-inner';
			
			
			// exception for the overlay (allows to apply over styles from media holder)
			if (element === 'tg-item-overlay') {	
			
				selector = target+' .tg-item-media-holder'+pseudo+' .'+element+':not(.tg-line-break),'+
					       target+' .tg-light .tg-item-media-holder'+pseudo+' .'+element+':not(.tg-line-break),'+
					       target+' .tg-dark .tg-item-media-holder'+pseudo+' .'+element+':not(.tg-line-break) {';
						   
			} else {
				
				selector = target+' .'+element+':not(.tg-line-break)'+pseudo+','+
					       target+' .tg-light .'+element+':not(.tg-line-break)'+pseudo+','+
					       target+' .tg-dark .'+element+':not(.tg-line-break)'+pseudo+'{';
						   
			
			}

			if (str_css || font_family) {	
			
				if ($styles.length) {
					$styles.text((str_css) ? selector+str_css+'}' : '');
					if (font_family) {
						font_family  = (state === 'idle_state' && font_family) ? process_font_family(arr_css) : '';
						str_font = (str_css) ? selector+font_family+'}' : '';
						$('style[class=\''+element+'\'][data-settings="font_family"]').text(str_font);
					}
					
				} else {
					if (state === 'idle_state') {
						str_font = process_font_family(arr_css);
						str_font = (str_font) ? selector+str_font+'}' : '';
						$('.tg-skin-elements-css').append(
							'<style type="text/css" class=\''+element+'\' data-settings="font_family">'+
								str_font+
							'</style>'
						);
					}
					$('.tg-skin-elements-css').append(
						'<style type="text/css" class=\''+element+'\' data-settings="'+state+'">'+
							selector+str_css+'}'+
						'</style>'
					);
				}
			} else {
				
				$styles.text('');
				
			}
			
			element_helper_size($('.'+element), state);
			
		}
			
	}
	
	function element_helper_size(el, state) {
		
		if (state === 'idle_state') {
			
			var top      = -parseFloat(el.css('marginTop'))-parseFloat(el.css('borderTopWidth')),
				left     = -parseFloat(el.css('marginLeft'))-parseFloat(el.css('borderLeftWidth')),
				bottom   = -parseFloat(el.css('marginBottom'))-parseFloat(el.css('borderBottomWidth')),
				right    = -parseFloat(el.css('marginRight'))-parseFloat(el.css('borderRightWidth'));
				
			el.find('> .tg-element-helper').css({
				'top'    : (top > 0 ? 0 : top)+'px',
				'left'   : (left > 0 ? 0 : left)+'px',
				'bottom' : (bottom > 0 ? 0 : bottom)+'px',
				'right'  : (right > 0 ? 0 : right)+'px'
			});
			
		}	
	
	}
	
	// process position rule
	function process_position(arr_css) {
		
		return check_value('position', arr_css) ? 'position:'+arr_css.position+';' : '';
		
	}
	
	// process display rule
	function process_display(arr_css) {
		
		return (check_value('display', arr_css) && check_value('position', arr_css) !== 'absolute') ? 'display:'+arr_css.display+';' : 'display:block;';
		
	}
	
	// process float rule
	function process_float(arr_css) {
		
		if (check_value('display', arr_css) === 'inline-block') {
		
			var important = check_value('float-important', arr_css) ? ' !important' : '';
			
			return check_value('float', arr_css) ? 'float:'+arr_css.float+important+';' : '';
		
		}
		
		return '';
		
	}
	
	// process absolute positions
	function process_positions(arr_css, element) {
		
		if (check_value('position', arr_css) === 'absolute' || element === 'tg-item-overlay') {
			
			var ps_un = check_value('positions-unit', arr_css, 'px');
			var positions = (arr_css.top !== undefined && $.isNumeric(arr_css.top))    ? 'top:'+arr_css.top+ps_un+';' : '';
			positions += (arr_css.bottom !== undefined && $.isNumeric(arr_css.bottom)) ? 'bottom:'+arr_css.bottom+ps_un+';' : '';
			positions += (arr_css.left !== undefined && $.isNumeric(arr_css.left))     ? 'left:'+arr_css.left+ps_un+';' : '';
			positions += (arr_css.right !== undefined && $.isNumeric(arr_css.right))   ? 'right:'+arr_css.right+ps_un+';' : '';

			return positions;

		}
		
		return '';
		
	}
	
	// process position absolute rule
	function process_zindex(arr_css, element) {
			
		var z_index = (check_value('position', arr_css) === 'absolute') ? 3 : '';
		
		return (z_index > 0) ? 'z-index:'+z_index+';' : '';
	
	}
	
	// process size rules
	function process_sizes(arr_css) {
		
		var width_un    = check_value('width-unit', arr_css, 'px'),
			width_imp   = check_value('width-important', arr_css) ? ' !important' : '',
			height_un   = check_value('height-unit', arr_css, 'px'),
			height_imp  = check_value('height-important', arr_css) ? ' !important' : '';
		
		var size = (arr_css.width !== undefined && $.isNumeric(arr_css.width)) ? 'width:'+arr_css.width+width_un+width_imp+';' : '';
		size += (arr_css.width !== undefined && $.isNumeric(arr_css.width))    ? 'min-width:'+arr_css.width+width_un+width_imp+';' : '';
		size += (arr_css.height !== undefined && $.isNumeric(arr_css.height))  ? 'height:'+arr_css.height+height_un+height_imp+';' : '';
		size += (arr_css.height !== undefined && $.isNumeric(arr_css.height))  ? 'min-height:'+arr_css.height+height_un+height_imp+';' : '';
		
		return (size) ? size : '';
	
	}
	
	// process margin rule
	function process_margin(arr_css) {
		
		var mg_css = get_css_directions(
			'margin-top',
			'margin-right',
			'margin-bottom',
			'margin-left',
			'margin-unit',
			'margin-important',
			arr_css
		);
		return (mg_css) ? 'margin: '+mg_css+';' : '';
		
	}
	
	// process padding rules
	function process_padding(arr_css) {
			
		var pd_css = get_css_directions(
			'padding-top',
			'padding-right',
			'padding-bottom',
			'padding-left',
			'padding-unit',
			'padding-important',
			arr_css
		);
		return (pd_css) ? 'padding: '+pd_css+';' : '';
		
	}
	
	// process border width
	function process_border_width(arr_css) {
			
		var bw_css = get_css_directions(
			'border-top',
			'border-right',
			'border-bottom',
			'border-left',
			'border-unit',
			'border-important',
			arr_css
		);
		return (bw_css) ? 'border-width: '+bw_css+';' : '';

	}
	
	// process border radius
	function process_border_radius(arr_css) {
		
		var br_css = get_css_directions(
			'border-top-left-radius',
			'border-top-right-radius',
			'border-bottom-right-radius',
			'border-bottom-left-radius',
			'border-radius-unit',
			'border-radius-important',
			arr_css
		);
		return (br_css) ? 'border-radius: '+br_css+';' : '';
		
	}
	
	// process box-shadow rules
	function process_box_shadows(arr_css) {
		
		var important = check_value('box-shadow-important', arr_css) ? ' !important' : '',
			sd_co     = check_value('box-shadow-color', arr_css, 'rgba(0,0,0,0)'),
			sdi_co    = check_value('box-shadow-inset-color', arr_css, 'rgba(0,0,0,0)');
		
		var sd_css = get_css_directions(
			'box-shadow-horizontal',
			'box-shadow-vertical',
			'box-shadow-blur',
			'box-shadow-size',
			'box-shadow-unit',
			null,
			arr_css
		);
		sd_css = (sd_css) ? sd_css+' '+sd_co : '';

		var sdi_css = get_css_directions(
			'box-shadow-inset-horizontal',
			'box-shadow-inset-vertical',
			'box-shadow-inset-blur',
			'box-shadow-inset-size',
			'box-shadow-inset-unit',
			null,
			arr_css
		);
		sdi_css = (sdi_css) ? 'inset '+sdi_css+' '+sdi_co : '';
		sdi_css = (sd_css && sdi_css)  ? ', '+sdi_css : sdi_css;
		
		if (sd_css || sdi_css) {
			var css_rule  = '-webkit-box-shadow:'+sd_css+sdi_css+important+';';
			css_rule += '-moz-box-shadow:'+sd_css+sdi_css+important+';';
			css_rule += 'box-shadow:'+sd_css+sdi_css+important+';';
			return css_rule;
		}
		
		return '';
		
	}

	// process text-shadow rules
	function process_text_shadows(arr_css) {

		var important = check_value('text-shadow-important', arr_css) ? ' !important' : '',
			ts_co     = check_value('text-shadow-color', arr_css, 'rgba(0,0,0,0)');
			
		var ts_css = get_css_directions(
			'text-shadow-horizontal',
			'text-shadow-vertical',
			'text-shadow-blur',
			null,
			'text-shadow-unit',
			null,
			arr_css,
			3
		);
		
		return (ts_css) ? 'text-shadow:'+ts_css+' '+ts_co+important+';' : '';
		
	}
	
	// process background-image rule
	function process_background_image(arr_css) {
		
		var important = check_value('background-image-important', arr_css) ? ' !important' : '';

		return (check_value('background-image', arr_css)) ? 'background-image:url('+arr_css['background-image']+')'+important+';' : '';
		
	}
	
	// process font-family
	function process_font_family(arr_css) {
		
		return check_value('font-family', arr_css) ? 'font-family:'+arr_css['font-family']+';' : '';
		
	}
	
	// process font family for available elements
	$(document).ajaxSuccess(function( event, xhr, settings ) {
		
		if(settings && settings.data && settings.data.indexOf('tg_get_elements') >= 0) {
			
			if (typeof TG_skin_elements === 'object') {

				for (var slug in TG_skin_elements) {

					settings = JSON.parse(TG_skin_elements[slug]);
					
					if (settings.hasOwnProperty('styles')) {
						
						tg_load_font(
							settings.styles.idle_state['font-family'],
							settings.styles.idle_state['font-weight'],
							settings.styles.idle_state['font-subset']
						
						);
						
					}
					
				}
				
			}
		}
		
	});

	// load Google Fonts
	function tg_load_font(font_family, font_weight, font_subset) {

		if (typeof font_family !== 'undefined') {
			
			if (tg_fonts[0].hasOwnProperty(font_family)) {
				
				font_family  = font_family;
				
				font_weight  = (font_weight) ? ':'+font_weight : '';
				font_subset  = (font_subset) ? '&'+font_subset : '';
				var font_url = font_family+font_weight+font_subset;

				if ($.inArray(font_url, tg_font_arrays) === -1) {
					tg_font_arrays.push(font_url);
					WebFont.load({
						google: {families:[font_url]}
					});
				}
				
			}
			
		}
	
	}
	
	// process custom rules
	function process_custom_rules(arr_css) {
		return (arr_css['custom-rules'] !== undefined && arr_css['custom-rules']) ? sanitize_CSS(arr_css['custom-rules']) : '';
	}
		
	// process visibility rules
	function process_visibility(element, state, arr_css) {
		
		var str_css    = '',	
			visibility_important = (arr_css['visibility-important']) ? ' !important' : '',
			overflow_important = (arr_css['overflow-important']) ? ' !important' : '',
			opacity_important = (arr_css['opacity-important'] || state === 'hover_state') ? ' !important' : '';
			
		str_css += (arr_css.visibility) ? 'visibility: '+arr_css.visibility+visibility_important+';' : '';
		str_css += (arr_css.overflow) ? 'overflow: '+arr_css.overflow+overflow_important+';' : '';
		str_css += (arr_css.opacity) ? 'opacity: '+arr_css.opacity+opacity_important+';' : '';

		if (state === 'idle_state') {
			
			var $styles = $('style[class=\''+element+'\'][data-settings=\'visibility\']');
			
			if (!str_css) {
				
				$styles.html('');
				
			} else if (str_css) {
			
				if ($styles.length) {
					
					$styles.text('.tg-skin-build-inner.tg-item-preview .'+element+'{'+str_css+'}');
					
				} else {

					$('.tg-skin-elements-css').append(
						'<style type="text/css" class=\''+element+'\' data-settings="visibility">'+
						'.tg-skin-build-inner.tg-item-preview .'+element+':not(.tg-line-break){'+
							str_css+
						'}'+
						'</style>'
					);
				}
			
			}			
			
		} else if (state === 'hover_state') {
			
			return str_css;
			
		}
		
		return '';
	
	}
	
	// ======================================================
	// Generate css animation
	// ======================================================
	
	function pre_process_animation(prefix, element, settings) {

		// fetch transform settings
		var animations = (settings)    ? settings.animation : '',
			name       = (!animations) ? $('[name="'+prefix+'_animation_name"]').val() : animations.animation_name,
			type       = (!animations) ? $('[name="'+prefix+'_animation_type"]').val() : animations.animation_type,
			position   = (!animations) ? $('[name="'+prefix+'_animation_position"]').val() : animations.animation_position,
			from       = (!animations) ? $('[name="'+prefix+'_animation_from"]').val() : animations.animation_from,
			easing     = (!animations) ? $('[name="'+prefix+'_animation_easing"]').val() : animations.animation_easing,
			cus_easing = (!animations) ? $('[name="'+prefix+'_animation_custom_easing"]').val() : animations.animation_custom_easing,
			duration   = (!animations) ? $('[name="'+prefix+'_animation_duration"]').val()+'ms' : animations.animation_duration+'ms',
			delay      = (!animations) ? $('[name="'+prefix+'_animation_delay"]').val()+'ms' : animations.animation_delay+'ms',
			translateu = (!animations) ? $('[name="'+prefix+'_translate-unit"]').val() : animations['translate-unit'],
			translateX = (!animations) ? $('[name="'+prefix+'_translatex"]').val() : animations.translatex,
			translateY = (!animations) ? $('[name="'+prefix+'_translatey"]').val() : animations.translatey,
			translateZ = (!animations) ? $('[name="'+prefix+'_translatez"]').val() : animations.translatez,
			rotateX    = (!animations) ? $('[name="'+prefix+'_rotatex"]').val() : animations.rotatex,
			rotateY    = (!animations) ? $('[name="'+prefix+'_rotatey"]').val() : animations.rotatey,
			rotateZ    = (!animations) ? $('[name="'+prefix+'_rotatez"]').val() : animations.rotatez,
			scaleX     = (!animations) ? $('[name="'+prefix+'_scalex"]').val() : animations.scalex,
			scaleY     = (!animations) ? $('[name="'+prefix+'_scaley"]').val() : animations.scaley,
			scaleZ     = (!animations) ? $('[name="'+prefix+'_scalez"]').val() : animations.scalez,
			skewX      = (!animations) ? $('[name="'+prefix+'_skewx"]').val() : animations.skewx,
			skewY      = (!animations) ? $('[name="'+prefix+'_skewy"]').val() : animations.skewy,
			originX    = (!animations) ? $('[name="'+prefix+'_originx"]').val() : animations.originx,
			originY    = (!animations) ? $('[name="'+prefix+'_originy"]').val() : animations.originy,
			originZ    = (!animations) ? $('[name="'+prefix+'_originz"]').val() : animations.originz,
			perspective= (!animations) ? $('[name="'+prefix+'_perspective"]').val() : animations.perspective;
			
			easing     = (easing === 'custom-easing') ? cus_easing : easing;
			easing     = (timing_functions.hasOwnProperty(easing)) ? timing_functions[easing] : easing;
			duration   = (duration && duration !== '0ms') ? duration : 0;
			delay      = (delay && delay !== 'ms') ? delay : '';
			
			// prepare transform values
			translateX = (translateX) ? translateX+translateu : 0;
			translateY = (translateY) ? translateY+translateu : 0;
			translateZ = (translateZ) ? translateZ+'px' : 0;
			rotateX    = (rotateX) ? ' rotateX('+rotateX+'deg)' : '';
			rotateY    = (rotateY) ? ' rotateY('+rotateY+'deg)' : '';
			rotateZ    = (rotateZ) ? ' rotateZ('+rotateZ+'deg)' : '';
			scaleX     = (scaleX) ? scaleX : 1;
			scaleY     = (scaleY) ? scaleY : 1;
			scaleZ     = (scaleZ) ? scaleZ : 1;
			skewX      = (skewX) ? ' skewX('+skewX+'deg)' : '';
			skewY      = (skewY) ? ' skewY('+skewY+'deg)' : '';
			originX    = (originX) ? originX : 0;
			originY    = (originY) ? originY : 0;
			originZ    = (originZ) ? originZ : 0;
			perspective= (perspective) ? 'perspective('+perspective+'px) ' : '';
		
		// build transform properties
		var translate   = (translateX || translateY || translateZ) ? 'translate3d('+translateX+','+translateY+','+translateZ+')' : '',
			scale3d     = ((scaleX || scaleY || scaleZ) && (scaleX !== 1 || scaleY !== 1 || scaleZ !== 1)) ? ' scale3d('+scaleX+','+scaleY+','+scaleZ+')' : '',
			i_translate = (translate) ? 'translate3d(0,0,0)' : '',
			i_rotateX   = (rotateX) ? ' rotateX(0)' : '',
			i_rotateY   = (rotateY) ? ' rotateY(0)' : '',
			i_rotateZ   = (rotateZ) ? ' rotateZ(0)' : '',
			i_scale3d   = (scale3d) ? ' scale3d(1,1,1)' : '',
			i_skewX     = (skewX) ? ' skewX(0)' : '',
			i_skewY     = (skewY) ? ' skewY(0)' : '',
			transform   = perspective+translate+scale3d+rotateX+rotateY+rotateZ+skewX+skewY,
			initial     = perspective+i_translate+i_scale3d+i_rotateX+i_rotateY+i_rotateZ+i_skewX+i_skewY,
			transition  = (duration) ? duration+' '+easing+' '+delay : '',
			origin      = (originX || originY || originZ) ? originX+'% '+originY+'% '+originZ+'px' : '';

		// generate animation
		process_animation(element,name,type,position,from,transform,initial,transition,origin);
		
	}
	
	/*** add animation on element ***/
	function process_animation(element,name,type,position,from,transform,initial,transition,origin) {

		from = (!from) ? 'item' : from;
		var $holder_class = $('.tg-item .'+element),
			anim_selector = {
			'item': '.tg-item-inner:hover',
			'media': ' .tg-item-media-holder:hover',
			'top-content': ' .tg-item-content-holder[data-position=\"top\"]:hover',
			'bottom-content': ' .tg-item-content-holder[data-position=\"bottom\"]:hover'
		};
		
		if (from === 'parent' && element.indexOf('tg-element-') >= 0) {
			from = ($holder_class.closest('.tg-item-media-holder').length) ? 'media' : from;
			from = ($holder_class.closest('.tg-item-content-holder[data-position="top"]').length) ? 'top-content' : from;
			from = ($holder_class.closest('.tg-item-content-holder[data-position="bottom"]').length) ? 'bottom-content' : from;
		}
		
		element = (element === 'tg-item-media-inner') ? 'tg-item-media-image' : element;

		if (transform || name === 'fade_in') {
			
			var hover_animation = (position === 'from') ? initial : transform,
				hover_opacity   = (type === 'show') ? 1 : 0,
				idle_animation  = (position === 'from') ? transform  : initial,
				idle_opacity    = (type === 'show') ? 0  : 1;
				
			var str_css_idle = '',
				str_css_over = '';
				
			if (transition) {
				str_css_idle += '-webkit-transition: all '+transition+';';
				str_css_idle += '-moz-transition: all '+transition+';';
				str_css_idle += '-ms-transition: all '+transition+';';
				str_css_idle += 'transition: all '+transition+';';
			}
			
			if (origin) {
				str_css_idle += '-webkit-transform-origin: '+origin+';';
				str_css_idle += '-moz-transform-origin: '+origin+';';
				str_css_idle += '-ms-transform-origin: '+origin+';';
				str_css_idle += 'transform-origin: '+origin+';';
			}
		
			if (name !== 'fade_in') {
				str_css_idle += '-webkit-transform:'+idle_animation+';';
				str_css_idle += '-moz-transform:'+idle_animation+';';
				str_css_idle += '-ms-transform:'+idle_animation+';';
				str_css_idle += 'transform:'+idle_animation+';';
			}
			str_css_idle += (type === 'show' || type === 'hide') ? 'opacity:'+idle_opacity+';' : '';
			
			if (name !== 'fade_in') {
				str_css_over += '-webkit-transform:'+hover_animation+';';
				str_css_over += '-moz-transform:'+hover_animation+';';
				str_css_over += '-ms-transform:'+hover_animation+';';
				str_css_over += 'transform:'+hover_animation+';';
			}
			str_css_over += (type === 'show' || type === 'hide') ? 'opacity:'+hover_opacity+';' : '';
			
			$holder_class = $('.tg-item .'+element);
			
			var $styles     = $('style[class=\''+element+'\'][data-settings="animate"]'),
				idle_rules  = '.tg-item-preview .tg-item-inner .'+element+':not(.tg-line-break){'+str_css_idle+'}',
				hover_rules = '.tg-item-preview '+anim_selector[from]+' .'+element+':not(.tg-line-break){'+str_css_over+'}';

			if ($styles.length) {
				$styles.text(idle_rules+hover_rules);
				// redraw element to apply new animation
				/* jshint ignore:start */
				if ($holder_class.length) {
					$holder_class.hide();
					$holder_class.get(0).offsetHeight;
					$holder_class.removeAttr('style');
				}
				/* jshint ignore:end */
			} else {
				$('.tg-skin-elements-css').append('<style type="text/css" class=\''+element+'\' data-settings="animate">'+idle_rules+hover_rules+'</style>');
			}
			
		} else {
			$('style[class=\''+element+'\'][data-settings="animate"]').text('');
		}
		
	}
	
	/*** add animation on element ***/
	function process_global_css(css) {
		$('.tg-skin-elements-css').find('[data-settings="global"]').remove();
		if (css) {
			$('.tg-skin-elements-css').append('<style type="text/css" class="global" data-settings="global">'+sanitize_CSS(css)+'</style>');
		}
	}
	
	// ======================================================
	// Handle line break element
	// ======================================================
	
	function line_break_panel() {
		
		if ($('.tg-element-draggable.'+tg_element_name).hasClass('tg-line-break')) {
			$('[data-target="tg-component-sources"]').trigger('click');
			$('.tg-panel-element .tg-component-panel ul li:not([data-target="tg-component-sources"])').hide();
		} else {
			$('.tg-panel-element .tg-component-panel ul li:not([data-target="tg-component-sources"])').show();
		}
		
	}
	
	// ======================================================
	// Handle element settings
	// ======================================================
	
	var select_timeout,
		tg_select_element = false;
	
	// update settings in element panel
	function select_element($this) {
		
		$('.tg-panel-element').addClass('tg-visible');
	
		if (!$this.hasClass('tg-element-selected')) {	
			
			clearTimeout(select_timeout);
			tg_select_element = true;

			update_element_name($this);
			line_break_panel();
			
			var settings = $this.data('settings'),
			$element = $('.tg-panel-element');
			
			if (settings) {	
			
				var types = ['source', 'action', 'animation', 'hover_state', 'idle_state'];
				
				for (var i = 0, l = types.length; i < l; i++) {
						
					var prefix = $element.find('[data-settings="'+types[i]+'"]').data('prefix'),
						values = (types[i].indexOf('_state') == -1) ? settings[types[i]] : settings.styles[types[i]],
						$panel = $element.find('[data-settings="'+types[i]+'"]');
					
					$panel.find('input[name], select[name], text[name], textarea[name]').each(function() {
						
						var $this = $(this),
							name  = $this.attr('name').replace(prefix, ''),
							value = (values && name in values) ? values[name] : '';

						if ($this.is(':checkbox')) {
							$this.prop('checked', value);
						} else if ($this.is('select')) {
							value = (!value && !$this.data('clear')) ? $this.find('option:first').val() : value;
							$this.val(value);
						} else {
							$this.val(value);
						}						
						
						
					});
					
				}
				
				$element.find('[data-settings="styles"] [name="is_hover"]').prop('checked', settings.styles.is_hover);
				
				// update font-weight and font-subset select field
				update_font_weight(
					settings.styles.idle_state['font-family'],
					settings.styles.idle_state['font-weight'],
					settings.styles.idle_state['font-subset']
				);
				
				// update all fields
				update_select($element);
				update_colors($element);
				update_sliders($element);
				update_image($element);
				update_icon_field();
				
				// recheck for requiered fields regarding previously updated fields
				TOMB_RequiredField.check();
				
				select_timeout = setTimeout(function(){
					tg_select_element = false;
				}, 80);
				
			}
			
		}
		
	}
	
	// save element settings
	function save_element_settings() {

		if (tg_element_name && !tg_select_element) {

			skin_was_modified = true;
			
			var arr_data  = {};

			// get element settings
			$('.tg-panel-element > div > [data-settings]').each(function() {
				
				var prefix = $(this).data('prefix');
	
				if ($(this).find('[data-settings]').length) {
					
					var type = $(this).data('settings');
					arr_data[type] = {};

					$(this).find('[data-settings]').each(function() {
						prefix = $(this).data('prefix');
						arr_data[type][$(this).data('settings')] = TG_field_value($(this), prefix);
					});
					
					if (type == 'styles') {
						var is_hover = ($(this).find('[name="is_hover"]').is(':checked')) ? true : '';
						arr_data[type].is_hover = is_hover;
					}
					
				} else {
					
					arr_data[$(this).data('settings')] = TG_field_value($(this), prefix);
					
				}
				
			});

			$('.tg-element-draggable.'+tg_element_name).data('settings', arr_data);
			
		}
		
	}
	
	// ======================================================
	// Helper for element content
	// ======================================================

	function format_element() {

		var $element = $('.tg-element-selected');
	
		if ($element.length) {
			
			var	settings      = $element.data('settings'),
				source_type   = settings.source.source_type,
				post_content  = settings.source.post_content,
				woo_content   = settings.source.woocommerce_content,
				video_content = settings.source.video_stream_content,
				content;
	
			if (source_type === 'post') {
				switch(post_content) {
					case 'get_the_excerpt':
						content = trim_excerpt(settings);
						break;
					case 'get_the_date':
						content = date_format(settings);
						break;
					case 'get_the_author':
						var prefix = settings.source.author_prefix;
						prefix  = prefix ? '<span class="tg-item-author-prefix">'+prefix+' </span>' : '';
						content = prefix+'<span class="tg-item-author-name">'+elements_content[post_content]+'</span>';
						break;
					case 'get_the_author_avatar':
						content = elements_content[post_content];
						break;
					case 'get_the_comments_number':
						content = comment_number(settings);
						break;
					case 'get_the_likes_number':
						content = like_number(settings);
						break;
					case 'get_the_terms':
						content = terms_format(settings);
						break;
					case 'get_the_meta_data':
						content = '_meta: '+settings.source.metadata_key;
						break;
					default:
						content = elements_content[post_content];
				}
			} else if (source_type === 'woocommerce') {
				switch(woo_content) {
					case 'get_product_rating':
						content = rating_star(settings);
						break;
					case 'get_product_cart_button':
						content = cart_button(settings);
						break;
					case 'get_product_add_to_cart_url':
						content = settings.source.add_to_cart_url_text;
						content = (content) ? content : elements_content[woo_content];
						break;
					default:
						content = elements_content[woo_content];
				}
			} else if (source_type === 'video_stream') {
				switch(video_content) {
					case 'get_the_views_number':
						var suffix = (settings.source.view_number_suffix) ? ' '+elements_content.views_number_suffix : '';
						content = elements_content[video_content]+suffix;
						break;
					case 'get_the_duration':
						content = elements_content[video_content];
						break;
					default:
						content = '';
				}
			} else if (source_type === 'media_button') {
				content = lightbox_content(settings);
			} else if (source_type === 'social_link') {
				var type = (settings.source.social_link_type) ? settings.source.social_link_type : 'facebook';
				content = '<i class="tg-social-share tg-'+type+' tg-icon-'+type+'"></i>';
			} else if (source_type === 'icon') {
				content = '<i class="'+settings.source.element_icon+'"></i>';
			} else if (source_type === 'html') {
				content = sanitize_HTML(settings.source.html_content);
			} else if (source_type === 'line_break') {
				content = sanitize_HTML(elements_content.line_break);
				$element.data('settings').styles.idle_state.position = 'relative';
				$('[name="element_idle_position"]').val('relative');
				update_select($('.element_idle_position'));
				if ($element.closest('.tg-area-droppable').hasClass('tg-item-media-content')) {
					$('.tg-item-overlay-content[data-position="top"]').append($element);
					check_dropped_element();
				}
				style_change(tg_element_name, $element.data('settings').styles.idle_state, 'idle_state');
			} 
			
			// add content (preserve tg-element-helper for performance)
			$element.contents().filter(function() {return this.nodeType == 3;}).remove();
			$element.contents(':not(.tg-element-helper)').remove();
			$element.prepend(content);	
	
			TG_element_color('.'+tg_element_name, settings);
			
			// handle line break
			line_break_panel();
		
		}
		
	}
	
	function trim_excerpt(settings) {
		
		var length  = settings.source.excerpt_length,
			suffix  = settings.source.excerpt_suffix;
		
		length = (Number(length) === 0 || !length) ? 240 : length;
		length = (Number(length) < 0) ? 999 : length;
			
		var content = elements_content.get_the_excerpt.substr(0, length);

		return content.substr(0, Math.min(content.length, content.lastIndexOf(' '))) + suffix;
		
	}
		
	function date_format(settings) {
		
		var date_format = settings.source.date_format;
			date_format = (date_format) ? date_format : elements_content.get_the_date;
			
		if (date_format == 'ago') {
			return '1 day ago';
		} else {
			return date_to_string(date_format);
		}
	}
	
	function comment_number(settings) {
		
		var comment_icon = settings.source.comment_icon,
			margin = [];
			
		if (comment_icon) {
			
			margin['margin-unit']   = settings.source['comment_icon_margin-unit'];
			margin['margin-top']    = settings.source['comment_icon_margin-top'];
			margin['margin-right']  = settings.source['comment_icon_margin-right'];
			margin['margin-bottom'] = settings.source['comment_icon_margin-bottom'];
			margin['margin-left']   = settings.source['comment_icon_margin-left'];
			margin = process_margin(margin);
			
			var font_size = settings.source['comment_icon_font-size'],
				font_unit = settings.source['comment_icon_font-size-unit'],
				font      = (font_size && font_unit) ? 'font-size:'+font_size+font_unit+'!important;' : '',
				line      = (font_size && font_unit) ? 'line-height:'+font_size+font_unit+'!important;' : '',
				float     = settings.source.comment_icon_float,
				color     = (settings.source.comment_icon_color) ? 'color:'+settings.source.comment_icon_color+'!important;' : '';
				float     = (float) ? 'float:'+float+';' : 'float:left!important;';
				
			var style = ' style="position:relative;display:inline-block;padding: 0 1px;'+color+font+line+float+margin+'"';

			return '<i class="'+comment_icon+'"'+style+'></i><span>2</span>';
			
		} else {
			return elements_content.get_the_comments_number;
		}
	}
	
	function like_number(settings) {
		
		var margin = [];
			
		margin['margin-unit']   = settings.source['like_icon_margin-unit'];
		margin['margin-top']    = settings.source['like_icon_margin-top'];
		margin['margin-right']  = settings.source['like_icon_margin-right'];
		margin['margin-bottom'] = settings.source['like_icon_margin-bottom'];
		margin['margin-left']   = settings.source['like_icon_margin-left'];
		margin = process_margin(margin);
			
		var $markup   = $(elements_content.get_the_likes_number),
			font_size = settings.source['like_icon_font-size'],
			font_unit = settings.source['like_icon_font-size-unit'],
			font      = (font_size && font_unit) ? font_size+font_unit : '',
			float     = settings.source.like_icon_float,
			color     = settings.source.like_icon_color;
			float     = (float) ? 'float:'+float+';' : 'float:left!important;';
		
		var style = 'position:relative;display:inline-block;'+float+margin;	
		
		$markup.find('.to-heart-icon').attr('style',style);
		if (font) {
			$markup.find('.to-heart-icon svg').css({
				'height': font,
				'width': font
			});
		}

		if (color) {
			$markup.find('.to-heart-icon svg path').attr('style','fill:'+color+'!important;stroke:'+color+'!important');
		}
		
		return $markup.html();

	}
	
	function lightbox_content(settings) {
		
		var content_type = settings.source.lightbox_content_type;
		
		if (content_type === 'text' && settings.source.lightbox_image_text) {
			return settings.source.lightbox_image_text;
		} else {
			return '<i class="'+settings.source.lightbox_image_icon+'"></i>';
		}
	
	}
	
	function terms_format(settings) {
		
		var separator = $('[name="terms_separator"]').val(),
			content   = elements_content.get_the_terms,
			padding   = [];
		
		padding['padding-unit']   = settings.source['terms_padding-unit'];
		padding['padding-top']    = settings.source['terms_padding-top'];
		padding['padding-right']  = settings.source['terms_padding-right'];
		padding['padding-bottom'] = settings.source['terms_padding-bottom'];
		padding['padding-left']   = settings.source['terms_padding-left'];
		padding = process_padding(padding);
		
		var style  = 'style="position:relative;display:inline-block;'+padding+'"';
			separator = (separator) ? '<span>'+separator+'</span>' : '';
		
		return '<span class="tg-item-term" '+style+'>'+content+'1</span>'+separator+'<span class="tg-item-term" '+style+'>'+content+'2</span>';
	
	}
	
	function rating_star(settings) {
		
		var color_empty = settings.source.woo_star_color_empty ? settings.source.woo_star_color_empty : '#cccccc',
			color_fill  = settings.source.woo_star_color_fill ? settings.source.woo_star_color_fill : '#e6ae48',
			font_size   = settings.source['woo_star_font-size'] ? settings.source['woo_star_font-size'] : 13,
			font_unit   = settings.source['woo_star_font-size-unit'] ? settings.source['woo_star_font-size-unit'] : 'px',
			line_height = 'line-height:'+font_size+font_unit+'!important;';

		color_empty = 'color:'+color_empty+'!important;';
		color_fill  = 'color:'+color_fill+'!important;';
		font_size   = 'font-size:'+font_size+font_unit+'!important;';

		return '<div class="tg-item-rating"><div class="star-rating" style="'+color_empty+font_size+line_height+'"><span style="width:90%!important;'+color_fill+'"></span></div></div>';
	
	}
	
	function cart_button(settings) {
		
		if (settings.source.woo_cart_icon) {
			
			var icon_simple = settings.source.woo_cart_icon_simple ? settings.source.woo_cart_icon_simple : 'tg-icon-shop-bag';
			return '<div class="add_to_cart_button"><i class="'+icon_simple+'"></i></div>';	
		
		} else {
		
			return elements_content.get_product_cart_button;	
			
		}
	
	}

	// ======================================================
	// Handle main tab content
	// ======================================================

	// hide tab content for style properties
	$('.tg-component-style-properties .tomb-tab').removeClass('selected');
	$('.tg-component-style-properties .tomb-tab-content').removeClass('tomb-tab-show').hide();
	
	// handle navigation in style properties
	$(document).on('click', '.tg-component-style-properties ul li', function() {
		
		var $this   = $(this),
			$holder = $this.closest('.tg-component-styles');
			
		$this.closest('.tg-component-style-properties').addClass('tg-move-tab');
		$holder.find('.tg-component-back span:nth-of-type(2)').html($holder.find('> ul .tomb-tab.selected').clone().children().remove().end().text());
		$holder.find('.tg-component-back span:last-child').html('<strong>'+$this.text()+'</strong>');
		
	});
	
	// auto close on click idle_state or hover_state
	$(document).on('click', '.tg-component-styles > ul li', function() {
		$(this).closest('ul').nextAll('.tg-move-tab').removeClass('tg-move-tab');		
	});
	
	// handle navigation for item panel (1st level)
	$(document).on('click', '.tg-panel-item .tg-container-content > ul li', function() {
		$('.tg-panel-item .tg-container-content').addClass('tg-move-tab');
		$('.tg-panel-item .tg-container-content > .tg-component-back span').html($(this).html());
	});
	
	// handle navigation for item panel (1st level)
	$(document).on('click', '.tg-panel-elements .tg-component-style-properties ul li', function() {
		$('.tg-panel-elements .tg-component-back span').html($(this).html());
	});
		
	// handle back buttons in a style property
	$(document).on('click', '.tg-component-back', function() {
		$(this).closest('.tg-move-tab').removeClass('tg-move-tab');
	});
	
	// ======================================================
	// Handle Slug name from skin name
	// ======================================================
	
	// skin slug from name for global custom css
	$(document).on('change input', '[name="skin_name"]', tg_debounce(function() {
		handle_slug($(this).val());
	}, 20));

	handle_slug($('[name="skin_name"]').val());
	
	function handle_slug(slug) {
		
		var lastClass = $('.tg-skin-build-inner .tg-item').attr('class').split(' ').pop();

		if (lastClass !== 'tg-item') {
			$('.tg-skin-build-inner .tg-item').removeClass(lastClass);
		}
		
		slug = 'tg-'+sanitize_string(removeDiacritics(slug));
		
		$('.tg-skin-slug').text(slug);
		$('.tg-skin-build-inner .tg-item').addClass(slug);
		
	}

	// ======================================================
	// Handle draggable panels
	// ======================================================
	
	// Element settings draggable popup
	$('.tg-panel-element, .tg-panel-elements').draggable({
		handle: '.tg-container-header',
		start: function() {
			$('.tg-icons-popup').removeClass('tg-icons-popup-open');	
			$('.tg-icon-holder').removeClass('tg-icon-is-open');
		}
	});
	
	// Element settings close popup
	$('.tg-panel-element .tg-container-close').on('click', function() {
		$('.tg-panel-element').removeClass('tg-visible');
		$('.tg-element-draggable').removeClass('tg-element-selected');
		update_element_list();
	});
	
	// Available Elements open popup
	$('#tg-add-element').on('click', function() {
		$('.tg-panel-elements').addClass('tg-visible');
	});
	
	// Available Elements close popup
	$('.tg-panel-elements .tg-container-close').on('click', function() {
		$('.tg-panel-elements').removeClass('tg-visible');
	});
	 
	// ======================================================
	// Calculate scrollbar width
	// ======================================================
	
	// calculate scrollbarwidth
	function scrollbar_width() {
	
		var body = document.body,
			box = document.createElement('div'),
			boxStyle = box.style,
			width;
	
		boxStyle.position = 'absolute';
		boxStyle.top = boxStyle.left = '-9999px';
		boxStyle.width = boxStyle.height = '100px';
		boxStyle.overflow = 'scroll';
	
		body.appendChild(box);
	
		width = box.offsetWidth - box.clientWidth;
	
		body.removeChild(box);
	
		return width;
		
	}
	
	var scrollbarWidth = scrollbar_width();
	
	// ======================================================
	// Custom ruler
	// ======================================================
		
	var _canvas 	= $('#tg-ruler-grid'),
		_cWidth 	= 2000,
		_cHeight	= 2000,
		_rulerOffset = { x : 0, y : 0},
		_mPos = { x:0, y:0 };

	// setup the rulers with counters and markers
	var hRuler 	= $('#tg-horizontalRuler'),
		vRuler 	= $('#tg-verticalRuler'),
		hMarker	= $('#tg-hMarker'),
		vMarker	= $('#tg-vMarker');

	function setupRulers() {
		
		var hCount = (_cWidth + 1) / 100,
			hList  = $('<ul/>'),
			vList  = $('<ul/>'),
			i = -20, k, _class;

		// clear out the counter first
		hRuler.find('ul').remove();
		vRuler.find('ul').remove();

		// add the coordinates list
		for ( ; i < hCount; i++) {
			k = i * 100;
			_class = (k === 0) ? ' class="tg-rule-line-first"' : '';
			hList.append( $('<li'+_class+'>' + k + '</li>').css('left', k) );
			vList.append( $('<li'+_class+'>' + k + '</li>').css('top', k) );
		}
		hRuler.css('width', _cWidth).prepend(hList);
		vRuler.css('height', _cHeight).prepend(vList);

	}
	setupRulers();
	
	function rulersOffset() {
		
		var ruler_bound = $('#tg-ruler-holder')[0].getBoundingClientRect(),
			item_bound  = $('.tg-item-inner')[0].getBoundingClientRect();

		_rulerOffset = {
			x : item_bound.left - ruler_bound.left - 1,
			y : item_bound.top - ruler_bound.top + 1
		};

		var pos_x = (_rulerOffset.x-39)/100,
			pos_y = (_rulerOffset.y-41)/100,
			off_x = pos_x.toFixed(2).toString().split('.'),
			off_y = pos_y.toFixed(2).toString().split('.');
			
		pos_x = (pos_x < 0) ? 100-parseInt(off_x[1]) : off_x[1];	
		pos_y = (pos_y < 0) ? 100-parseInt(off_y[1]) : off_y[1];
		
		hRuler.css('left', Math.ceil(_rulerOffset.x));
		vRuler.css('top', Math.ceil(_rulerOffset.y));
		_canvas.css('background-position', pos_x+'px '+pos_y+'px');
		$('.tg-skin-build-inner').css('background-position', pos_x+'px '+pos_y+'px');
		
	}
	
	
	$(window).on('resize', function(e) {
		rulersOffset();
	});
	
	// the main move event
	$('.tg-skin-build-inner').on('mousemove mouseleave', function(e) {

		var $this  = $(this),
			offset = $this.offset(),
			posx   = e.pageX - (offset.left + _rulerOffset.x),
			posy   = e.pageY - (offset.top + _rulerOffset.y);
			_mPos  = {
				x: (posx+_rulerOffset.x-40 >= 0) ? posx : -_rulerOffset.x+40,
				y: (posy+_rulerOffset.y-40 >= 0) ? posy : -_rulerOffset.y+40
			};

		hMarker.css('left', Math.ceil(_mPos.x));
		vMarker.css('top', Math.ceil(_mPos.y));
		hMarker.find('span').text(parseInt(Math.ceil(_mPos.x-1)));
		vMarker.find('span').text(parseInt(Math.ceil(_mPos.y+1)));

	});
	
	// ======================================================
	// On document ready init events
	// ======================================================

	$(document).ready(function(e) {
		
		rulersOffset();
		
		check_overlay_type();
		
		// update all fields
		if (typeof tg_skin_settings !== 'undefined') {
			var $element = $('.tg-panel-item');
			update_select($element);
			update_sliders($element);
			update_image($element);
			update_element_list();
		}
		
		// on inputs change for item settings
		$('.tg-panel-item').on('input change', function(){
			// display message if settings change before to quit the page
			skin_was_modified = true;
		});
		
		var panelWidth = $('.tg-panel-element').width();
		$('#element_source, .tg-panel-element .tomb-tab-content').addClass('force-show');
		$('#element_source, .tg-panel-element .tg-component-style-properties .tomb-tab-content').each(function(){
			var $this = $(this);
			$this.width(panelWidth+scrollbarWidth).css('overflow-y', 'scroll');
			if ($this.get(0).scrollHeight > $this.height()) {
				var H     = $this.height(),
					sH    = $this.get(0).scrollHeight,
					sbH   = H*H/sH;
				$('<div class="tg-scrollbar" style="height:'+(sbH-10)+'px"></div>').insertAfter($(this));
			}
		});
		$('#element_source, .tg-panel-element .tomb-tab-content').removeClass('force-show');
		
		$('#element_source, .tg-panel-element .tg-component-style-properties .tomb-tab-content').on('scroll', function(){

			var $this  = $(this),
				H      = $this.height(),
				sH     = $this.get(0).scrollHeight,
				sbH    = H*H/sH,
				offset = $this.scrollTop()/H*sbH,
				height = (sH-H > 0) ? sbH : 0;

			offset = ($(this).is('#element_source')) ? offset+50: offset;
			$(this).next('.tg-scrollbar').css('top', offset+5).height(height-10).show();

		});
		
		$('.tg-panel-element .tomb-tab-content, #element_source').on('mouseenter', function(){
			$(this).trigger('scroll');
		}).on('mouseleave', function(){
			$(this).next('.tg-scrollbar').hide();
		});
		
		
		var grid_size = 10;
		
		// change ruler grid size
		$('[name="tg-ruler-grid-size"]').on('change', function(e) {
			
			var $this = $(this),
				value = $this.val();
				
			grid_size = $this.find(':selected').data('grid');
				
			_canvas.attr('class', '');
			_canvas.addClass(value);
			$('.tg-area-droppable').sortable('refresh');
			
		});
		
		// refresh sortable force snap feature
		$('[name="tg-ruler-grid-snap"]').on('change', function(e) {
			$('.tg-area-droppable').sortable('refresh');
		});
		
		/*** resize draggable from helper ***/
		var $width_input  = $('[name="element_idle_width"]'),
			$height_input = $('[name="element_idle_height"]'),
			$resizable_element,
			resizable_active,
			helper_pt,
			helper_pb,
			helper_pl,
			helper_pr,
			helper_wd,
			helper_ht,
			el_gap_x,
			el_gap_y;
			
		var resizable_options = {
			handles: 'all',
			start: function(event,ui) {
				
				event.preventDefault();
				$resizable_element = $(ui.element).closest('.tg-element-draggable');
				resizable_active = $resizable_element.hasClass('tg-element-selected');
				helper_pt = parseFloat($(ui.element).css('top'));
				helper_pb = parseFloat($(ui.element).css('bottom'));
				helper_pl = parseFloat($(ui.element).css('left'));
				helper_pr = parseFloat($(ui.element).css('right'));
				el_gap_x  = parseFloat($resizable_element.css('borderLeftWidth'))+parseFloat($resizable_element.css('borderRightWidth'));
				el_gap_y  = parseFloat($resizable_element.css('borderTopWidth'))+parseFloat($resizable_element.css('borderBottomWidth'));
				$resizable_element.addClass('is-resized');
				
			},
			resize:function(event,ui) {

				helper_wd = ui.size.width+helper_pl+helper_pr+el_gap_x+2;
				helper_ht = ui.size.height+helper_pt+helper_pb+el_gap_y+2;
				
				var settings    = $resizable_element.data('settings'),
					width_unit  = settings.styles.idle_state['width-unit'],
					height_unit = settings.styles.idle_state['height-unit'],
					$parent     = $resizable_element.parent();
				
				width_unit  = (!width_unit) ? 'px'  : width_unit;
				height_unit = (!height_unit) ? 'px' : height_unit;
				
				// calculate width in percent
				if (width_unit === '%') {
					helper_wd = helper_wd/$parent.outerWidth()*100;
				}
				// calculate height in percent
				if (height_unit === '%') {
					helper_ht = helper_ht/$parent.outerHeight()*100;
				}
				
				helper_wd = (helper_wd < 0) ? 0 : helper_wd;
				helper_ht = (helper_ht < 0) ? 0 : helper_ht;
				
				$resizable_element.css({
					'min-width'  : (ui.size.width != ui.originalSize.width) ? helper_wd+width_unit : '',
					'width'      : (ui.size.width != ui.originalSize.width) ? helper_wd+width_unit : '',
					'min-height' : (ui.size.height != ui.originalSize.height) ? helper_ht+height_unit : '',
					'height'     : (ui.size.height != ui.originalSize.height) ? helper_ht+height_unit : ''
				});
				$(ui.helper).css({
					'width'  : '',
					'height' : '',
					'top'    : helper_pt,
					'bottom' : helper_pb,
					'left'   : helper_pl,
					'right'  : helper_pr,
				});

				// set input in live if element activated
				if (resizable_active) {
					if (ui.size.width != ui.originalSize.width) {
						$width_input.val(helper_wd);
					}
					if (ui.size.height != ui.originalSize.height) {
						$height_input.val(helper_ht);
					}
				}

			},
			stop: function(event,ui) {
				
				skin_was_modified = true;
				
				var settings = $resizable_element.data('settings');
				
				if (ui.size.width != ui.originalSize.width) {
					settings.styles.idle_state.width  = helper_wd;
				}
				if (ui.size.height != ui.originalSize.height) {
					settings.styles.idle_state.height = helper_ht;
				}
				style_change($resizable_element.data('name'), settings.styles.idle_state, 'idle_state');
				$resizable_element.attr('style', '');
				
			}
		};
		$('.tg-element-draggable .tg-element-helper').resizable(resizable_options);
				
		/*** element size/position (subpixel) ***/
		var element_treshold,
			element_size;

		/*** Get size before dragging (deacrase calculatiosn while dragging) ***/
		$(document).on('mousedown', '.tg-element-draggable:not(.ui-sortable-helper)', function(){
			
			var $this = $(this);
			element_size = get_element_size($this);

			if (absolute_element($this)) {
				
				var item_offset   = $('.tg-item-inner').offset(),
					parent_offset = $this.parent().offset();
				
				element_treshold = {
					x : Math.floor(Math.round((parent_offset.left - item_offset.left) / grid_size) * grid_size - (parent_offset.left - item_offset.left)),
					y : Math.floor(Math.round((parent_offset.top - item_offset.top) / grid_size) * grid_size - (parent_offset.top - item_offset.top))
				};
					
			}

		});
		
		/*** Sort element in item builder areas ***/
		var absolute_position,
			$tg_current_area,
			tg_grid_snap  = false,
			$top_input    = $('[name="element_idle_top"]'),
			$right_input  = $('[name="element_idle_right"]'),
			$bottom_input = $('[name="element_idle_bottom"]'),
			$left_input   = $('[name="element_idle_left"]');
			
		$('.tg-area-droppable').sortable({
			connectWith : '.tg-area-droppable:not(.tg-area-disabled)',
			revert      : true,
			zIndex      : 998,
			tolerance   : 'pointer',
			distance    : 5,
			appendTo    : '.tg-skin-build-inner',
			helper      : 'clone',
			items       : '.tg-element-draggable:not(.unsortable)',
			placeholder : 'tg-state-highlight',
			over: function(e, ui){
				
				if (!absolute_element($(ui.item))) {
					helper_size(ui);
				}
				
			},
			start: function(e, ui) {				
				
				var $element = $(ui.item),
					helper_style = $element.find('.tg-element-helper').attr('style'),
					element_icon = $('<div class="tg-element-helper"></div>').css({
						'top'    : -Math.max(0, parseFloat($element.css('marginTop'))),
						'left'   : -Math.max(0, parseFloat($element.css('marginLeft'))),
						'bottom' : -Math.max(0, parseFloat($element.css('marginBottom'))),
						'right'  : -Math.max(0, parseFloat($element.css('marginRight')))
					});

				$(ui.placeholder).addClass('tg-no-hash').html(element_icon);
				
				$tg_current_area = $element.parent();
				$tg_current_area.css('overflow', 'visible');
				helper_size(ui);
				refresh_sortable_start($element);
				check_dropped_element_start($element);

			},
			sort: function(e, ui) {

				var $element      = $(ui.item),
					$parent       = $element.parent(),
					$ruler        = $('#tg-ruler-holder'),	
					is_active     = $element.hasClass('tg-element-selected'),
					settings      = $element.data('settings'),
					position_unit = settings.styles.idle_state['positions-unit'],
					position_from = settings.styles.idle_state['positions-from'],
					ruler_offset  = $ruler.offset(),
					ruler_width   = $ruler.width(),	
					ruler_height  = $ruler.height(),
					parent_offset = $parent.offset(),
					parent_width  = $parent.outerWidth(),
					parent_height = $parent.outerHeight(),
					bound_right   = ruler_width  + 40 - element_size.outerWidth,
					bound_bottom  = ruler_height + 91 - element_size.outerHeight;
					
					position_unit = (!position_unit) ? 'px'  : position_unit;
					position_from = (!position_from) ? 't/l' : position_from;
				
				// if negative left margin prevent overflow from ruler
				var correction = {
					x : element_size.margin.left < 0 ? element_size.margin.left : 0,
					y : element_size.margin.top < 0 ? element_size.margin.top : 0
				};
				
				// containment helper
				ui.position.left = (ui.position.left - 40 + correction.x <= 0) ? 40 - correction.x : ui.position.left;
				ui.position.left = (ui.position.left + correction.x >= bound_right)  ? bound_right - correction.x : ui.position.left;
				ui.position.top  = (ui.position.top - 141 + correction.y <= 0)  ? 141 - correction.y : ui.position.top;
				ui.position.top  = (ui.position.top + correction.y  >= bound_bottom) ? bound_bottom - correction.y : ui.position.top;

				if (absolute_element($element)) {
					
					var gap_x = 0, gap_y = 0,
						new_left, new_top,
						old_left = ui.offset.left,
						old_top  = ui.offset.top;
						
					// containment placeholder
					ui.offset.left = (ui.position.left - 40 + correction.x <= 0) ? ruler_offset.left + 40  - correction.x : ui.offset.left;
					ui.offset.left = (ui.position.left + correction.x >= bound_right)  ? ruler_offset.left + ruler_width + 40 - element_size.outerWidth - correction.x : ui.offset.left;
					ui.offset.top  = (ui.position.top - 141 + correction.y <= 0)  ? ruler_offset.top + 90 - correction.y : ui.offset.top;
					ui.offset.top  = (ui.position.top + correction.y  >= bound_bottom) ? ruler_offset.top + ruler_height + 40 - element_size.outerHeight - correction.y : ui.offset.top;
					
					// get old left position placeholder
					old_left = (ui.position.left - 40 <= 0) ? old_left : ui.offset.left;
					old_left = (ui.position.left >= bound_right)  ? old_left : ui.offset.left;
					old_top  = (ui.position.top - 141 <= 0)  ? old_top : ui.offset.top;
					old_top  = (ui.position.top  >= bound_bottom) ? old_top : ui.offset.top;

					// new placeholder position
					new_left = ui.offset.left - parent_offset.left;
					new_top  = ui.offset.top  - parent_offset.top;

					// round 5 to snap grid
					if (tg_grid_snap) {

						new_left = (Math.round(grid_size * Math.round((new_left)/grid_size))) + element_treshold.x;
						new_top  = (Math.round(grid_size * Math.round((new_top)/grid_size))) + element_treshold.y;
						
						ui.offset = {
							top  : (Math.round(new_top + parent_offset.top)),
							left : (Math.round(new_left + parent_offset.left)),
						};

						// calculate difference in px for helper
						gap_x = Math.floor(ui.offset.left - old_left);
						gap_y = Math.floor(ui.offset.top  - old_top);
						
					}

					// calculate new absolute position for element
					absolute_position = {
						top    : new_top,
						left   : new_left,
						bottom : parent_height - new_top - element_size.outerHeight,
						right  : parent_width - new_left - element_size.outerWidth
					};
					
					// calculate position in percent
					if (position_unit === '%') {
						absolute_position = {
							top    : absolute_position.top / parent_height * 100,
							left   : absolute_position.left / parent_width * 100,
							bottom : absolute_position.bottom / parent_height * 100,
							right  : absolute_position.right / parent_width * 100,
						};
					}
					
					// set input in live if element activated
					if (is_active) {
						if (position_from === 't/l' || position_from === 't/r') {
							$top_input.val(absolute_position.top);
							$bottom_input.val('');
						} else {
							$top_input.val('');
							$bottom_input.val(absolute_position.bottom);
						}
								
						if (position_from === 't/l' || position_from === 'b/l') {
							$left_input.val(absolute_position.left);
							$right_input.val('');
						} else {
							$left_input.val('');
							$right_input.val(absolute_position.right);
						}
					}
					
					// set new helper position
					$(ui.helper).css({
						'top'    : ui.position.top+gap_y+'px',
						'left'   : ui.position.left+gap_x+'px',
						'bottom' : 'auto',
						'right'  : 'auto'
					});
					
					// set new placeholder position
					$(ui.placeholder).css({
						'top'    : new_top+'px',
						'left'   : new_left+'px',
						'bottom' : 'auto',
						'right'  : 'auto'
					});

				} else {
					
					// for containment
					$(ui.helper).css({
						'top'    : ui.position.top+'px',
						'left'   : ui.position.left+'px',
						'bottom' : 'auto',
						'right'  : 'auto'
					});
										
				}
				
			},
			stop: function(e, ui){
				
				skin_was_modified = true;
				
				var $element = $(ui.item);
				
				check_dropped_element();
				update_element_list();
				$element.attr('style', '');
				$tg_current_area.attr('style', '');
				absolute_position_stop($element);
				refresh_sortable_stop();
				
			}
		}).disableSelection();
		
		/*** Refresh sortable on rize to prevent issue ***/
		$(window).resize(function(){
			refresh_sortable_stop();
		});
		
		function absolute_position_stop($element) {
			
			var settings      = $element.data('settings'),
				position      = settings.styles.idle_state.position,
				position_from = settings.styles.idle_state['positions-from'];
				position_from = (!position_from) ? 't/l' : position_from;
				
			if (position === 'absolute') {

				if (position_from === 't/l' || position_from === 't/r') {
					settings.styles.idle_state.top    = absolute_position.top;
					settings.styles.idle_state.bottom = '';
				} else {
					settings.styles.idle_state.top    = '';
					settings.styles.idle_state.bottom = absolute_position.bottom;
				}
						
				if (position_from === 't/l' || position_from === 'b/l') {
					settings.styles.idle_state.left  = absolute_position.left;
					settings.styles.idle_state.right = '';
				} else {
					settings.styles.idle_state.left  = '';
					settings.styles.idle_state.right = absolute_position.right;
				}
				
				settings.styles.idle_state['z-index'] = 3;
				style_change($element.data('name'), settings.styles.idle_state, 'idle_state');
			
			}
			
		}
		
		function absolute_element($element) {
			
			var settings = $element.data('settings'),
				position = settings.styles.idle_state.position;

			return (position === 'absolute') ? true : false;
		
		}
				
		function helper_size(ui) {
			
			var $element = $(ui.item),
				settings = $element.data('settings'),
				position = settings.styles.idle_state.position,
				display  = settings.styles.idle_state.display,
				source   = settings.source.source_type,
				align    = settings.styles.idle_state['text-align'],
				po_unit  = settings.styles.idle_state['positions-unit'],
				parent_width   = (source === 'line_break') ? $(ui.placeholder).parent().width() : null,
				margin_padding = {
					'paddingTop'    : (source !== 'line_break') ? element_size.padding.top : 0,
					'paddingBottom' : (source !== 'line_break') ? element_size.padding.bottom : 0,
					'paddingLeft'   : (source !== 'line_break') ? element_size.padding.left : 0,
					'paddingRight'  : (source !== 'line_break') ? element_size.padding.right : 0,
					'marginTop'     : (source !== 'line_break') ? element_size.margin.top : 0,
					'marginBottom'  : (source !== 'line_break') ? element_size.margin.bottom : 0,
					'marginLeft'    : (source !== 'line_break') ? element_size.margin.left : 0,
					'marginRight'   : (source !== 'line_break') ? element_size.margin.right : 0
				};

			$(ui.placeholder).css($.extend({
				'position'  : position,
				'display'   : (display !== 'inline') ? display : 'inline-block',
				'height'    : (source !== 'line_break') ? element_size.height : 10,
				'width'     : (source !== 'line_break') ? element_size.width : parent_width,
				'min-width' : (source !== 'line_break') ? element_size.width : parent_width,
				'max-width' : (source !== 'line_break') ? element_size.width : parent_width,
				'top'       : (position == 'absolute')  ? settings.styles.idle_state.top+po_unit    : 'none',
				'bottom'    : (position == 'absolute')  ? settings.styles.idle_state.bottom+po_unit : 'none',
				'left'      : (position == 'absolute')  ? settings.styles.idle_state.left+po_unit   : 'none',
				'right'     : (position == 'absolute')  ? settings.styles.idle_state.right+po_unit  : 'none',
				'float'     : (source !== 'line_break') ? settings.styles.idle_state.float : ''
			},margin_padding));
			
			if (source === 'line_break') {
				$(ui.placeholder).children().css({
					'top'    : 0,
					'right'  : 0,
					'bottom' : 0,
					'left'   : 0
				});
			}

			$(ui.helper).css($.extend({
				'width'      : (source !== 'line_break') ? element_size.width : parent_width,
				'min-width'  : (source !== 'line_break') ? element_size.width : parent_width,
				'max-width'  : (source !== 'line_break') ? element_size.width : parent_width,
				'height'     : (source !== 'line_break') ? element_size.height : 10,
				'min-height' : (source !== 'line_break') ? element_size.height : 10,
				'max-height' : (source !== 'line_break') ? element_size.height : 10,
				'text-align' : (!align) ? $(ui.placeholder).parent().css('text-align') : align
			},margin_padding));
			
			$(ui.helper)[0].style.setProperty( 'color',  $element.css('color'), 'important' );
			$(ui.helper)[0].style.setProperty( 'border-color',  $element.css('border-color'), 'important' );

		}
		
		/*** refresh sortable when drag start ***/
		function refresh_sortable_start(el) {
			
			var revert,
				settings = el.data('settings'),
				position = settings.styles.idle_state.position;
				
			// make all element responsive again
			refresh_sortable_stop();
			
			// disable snap
			tg_grid_snap = true;
			
			// if element is absolute
			if (position === 'absolute') {
					
				var snap = $('[name="tg-ruler-grid-snap"]').is(':checked'),
					size = $('[name="tg-ruler-grid-size"]').val();
					tg_grid_snap = (snap && size) ? true : false;
					revert = false;

				// prevent flicker by removing revert animation
				$('.tg-skin-build-inner .tg-area-droppable').addClass('tg-area-disabled');
				// make all elements unresponsive
				$('.tg-skin-build-inner .tg-element-draggable').addClass('unsortable');
				
			} else {
				
				// enable revert animation for relative element
				revert = true;
				// make all absolute elements unresponsive
				$('.tg-skin-build-inner .tg-item-media-content > .tg-element-draggable').addClass('unsortable');
				// disable media content area (top,center,bottom)
				$('.tg-skin-build-inner .tg-item-media-content').addClass('tg-area-disabled');
				
			}
			
			// refresh jquery ui sortable
			$('.tg-area-droppable').sortable({revert: revert});
			$('.tg-area-droppable').sortable('refresh');
			
		}
		
		// refresh sortable when drag stop
		function refresh_sortable_stop() {
			
			// make all element responsive again
			$('.tg-element-draggable').removeClass('unsortable');
			// re-enable all droppable areas
			$('.tg-area-droppable').removeClass('tg-area-disabled').sortable('refresh');
			
		}
		
		/*** select element from dropdown list element class name ***/
		$(document).on('change', '[name="tg-element-class"]', function() {
			var $element = $('.tg-element-draggable.'+$(this).val());
			select_element($element);
		});
		
		/*** Move up or down element ***/
		$(document).on('click', '.tg-element-move', function() {
			
			var $this    = $('.tg-element-selected'),
				move     = $(this).data('move'),
				absolute = absolute_element($this),
				selector = (absolute) ? 'absolute' : 'relative',
				not_in   = (absolute) ? '[data-item-area="media-holder-top"], [data-item-area="media-holder-center"], [data-item-area="media-holder-bottom"]' : '[data-item-area="media-holder"]',
				$item    = find_element($this, selector, move),
				$area    = $('[data-item-area]:visible').not(not_in),
				action, index;
			
			if ($item.length && !absolute) {
				action = (move === 'next') ? $this.insertAfter($item) : $this.insertBefore($item);
			} else {
				index =  $area.index($this.closest('[data-item-area]'));
				index = (move === 'next') ? index+1 : index-1;
				if (!$area.eq(index).length) {
					$area = (move === 'next') ? $area.first() : $area.last();
				} else {
					$area = $area.eq(index);
				}
				
				$item = find_element($this, $area.find('.tg-element-draggable'), move);
				if ($item.length) {
					action = (move === 'next') ? $this.insertBefore($item) : $this.insertAfter($item);
				} else {
					if (move === 'prev') {
						$area.append($this);
					} else {
						$area.prepend($this);
					}
				}
			}

			// check area if new item appended or removed
			check_dropped_element();
			update_element_list();
			
		});
		
		/*** check dropped element on position change ***/
		$(document).on('change', 'select[name="element_idle_position"]', function(e){

			var $element = $('.tg-element-selected'),
				settings = $element.data('settings'),
				position = $(this).val();
			
			settings.styles.idle_state['z-index'] = (position === 'absolute') ? 3 : '';

			if (position === 'absolute' && settings.styles.idle_state.position !== 'absolute' && $element.closest('.tg-item-overlay-content').length) {
				$('.tg-skin-build-inner .tg-item-media-content').append($element);
			} else if (position === 'relative' && settings.styles.idle_state.position === 'absolute' && $element.closest('.tg-item-media-content').length) {
				$('.tg-skin-build-inner .tg-top-holder').append($element);
			}
			
			// check area if new item appended or removed
			check_dropped_element();
			update_element_list();
			
		});
		
		/*** find elment in droppable area ***/
		function find_element(element, selector, position) {
			
			var $elements = (position === 'next') ? element.nextAll('.tg-element-draggable') : element.prevAll('.tg-element-draggable');

			var $element = $elements.filter(function(){
				if ($(this).css('position') === selector) {
					return $(this);
				}
			});
			
			return $element.first();
			
		}
	
		/*** select element on click ***/
		$(document).on('click', '.tg-element-draggable.tg-element-init:not(.ui-sortable-helper, .is-resized)', function(e) {
			select_element($(this));
		});
		
		/*** remove resize class on click anywhere to prevent selection when resizing ***/
		$(document).on('click', 'body *', function(e) {
			$('.tg-element-draggable.is-resized').removeClass('is-resized');
		});

		/*** update styles on color change ***/
		$('.tomb-colorpicker').wpColorPicker({
			change: tg_debounce(function(event, ui){
				update_element_color($(this));
			}, 20),
			clear: function() {
				update_element_color($(this));
			},
		});

		/*** preview skin mode ***/
		$('#tg-item-preview').on('click', function() {

			var $preview_btn = $('#tg-item-preview'),
				$3dview_btn  = $('#tg-3d-view');
			
			if (!$preview_btn.hasClass('is-previewed')) {
				
				apply_layer_depths();
				
				// add elements animations
				$('.tg-element-draggable.tg-element-init').each(function() {
					var element  = $(this).data('name'),
						settings = $(this).data('settings');
					pre_process_animation('element', element, settings);
				});
				
				// add item holders animations
				$('.tg-tab-media-animations [data-prefix]').each(function() {
					var prefix  = $(this).data('prefix').slice(0,-1),
						element = $(this).data('settings');
					pre_process_animation(prefix, element);
				});
				
				$preview_btn.addClass('is-previewed');
				$3dview_btn.removeClass('is-previewed');
				$('.tg-item-inner').addClass('tg-no-transition');
				$('.tg-skin-build-inner').removeClass('view-3d-mode').addClass('tg-item-preview');
				setTimeout(function(){
					$('.tg-item-inner').removeClass('tg-no-transition');
				}, 0);
				$('.tg-area-droppable').sortable('disable');
				
			} else {
				
				disable_layer_depths();
				$preview_btn.removeClass('is-previewed');
				$('.tg-skin-build-inner').removeClass('tg-item-preview');
				$('.tg-area-droppable').sortable('enable');
				
			}
			
		});
		
		/*** 3D view skin mode ***/
		$('#tg-3d-view').on('click', function() {
			
			var $preview_btn = $('#tg-item-preview'),
				$3dview_btn  = $('#tg-3d-view');
			
			if (!$3dview_btn.hasClass('is-previewed')) {
				
				apply_layer_depths();
				$3dview_btn.addClass('is-previewed');
				$preview_btn.removeClass('is-previewed');
				$('.tg-skin-build-inner').removeClass('tg-item-preview');
				setTimeout(function(){
					$('.tg-skin-build-inner').addClass('view-3d-mode');
				}, 10);
				$('.tg-area-droppable').sortable('disable');
				
			} else {
				
				disable_layer_depths();
				$3dview_btn.removeClass('is-previewed');
				$('.tg-skin-build-inner').removeClass('view-3d-mode');
				$('.tg-area-droppable').sortable('enable');
				
				// redraw element to apply new animation
				/* jshint ignore:start */
				var $item = $('.tg-item');
				$item.hide();
				$item.get(0).offsetHeight;
				$item.removeAttr('style');
				/* jshint ignore:end */

			}

		});
		
		/*** update layer depth ***/
		$(document).on('change', '.tg-tab-layer-depths select', function() {
			
			var $preview_btn = $('#tg-item-preview'),
				$3dview_btn  = $('#tg-3d-view');

			if ($3dview_btn.hasClass('is-previewed') || $preview_btn.hasClass('is-previewed')) {
				apply_layer_depths();
			}

		});

		/*** change font family ***/
		$(document).on('change', '[name="element_idle_font-family"], [name="element_idle_font-weight"], [name="element_idle_font-subset"]', function() {
			
			var font_family = $('[name="element_idle_font-family"]'),
				font_weight = $('[name="element_idle_font-weight"]'),
				font_subset = $('[name="element_idle_font-subset"]');
			
			// udpate font-weight & font-subset fields
			update_font_weight(
				font_family.val(),
				font_weight.val(),
				font_subset.val()
			);

			// load font with new font-weight & font-subset values previous set with update_font_weight
			tg_load_font(
				font_family.val(),
				font_weight.val(),
				font_subset.val()
			);	
				
		});
		
		/*** change positions from/values (units: px or % and top/left/bottom/right) ***/
		$(document).on('change', '[name="element_idle_positions-unit"], [name="element_idle_positions-from"]', function() {

			var po_unit   = $('[name="element_idle_positions-unit"]').val(),
				po_from   = $('[name="element_idle_positions-from"]').val(),
				$element  = $('.tg-element-draggable.'+tg_element_name),
				settings  = $element.data('settings'),
				offset    = $element.position(),
				el_bound  = get_element_size($element),
				pa_bound  = get_element_size($element.parent()),
				el_width  = el_bound.outerWidth,
				el_height = el_bound.outerHeight,
				pa_width  = pa_bound.width,
				pa_height = pa_bound.height,
				position;

			position = {
				top     : (po_unit === 'px') ? offset.top : offset.top / pa_height*100,
				left    : (po_unit === 'px') ? offset.left : offset.left / pa_width*100,
				right   : (po_unit === 'px') ? pa_width - (offset.left + el_width)  : (pa_width - (offset.left + el_width)) / pa_width*100,
				bottom  : (po_unit === 'px') ? pa_height - (offset.top + el_height) : (pa_height - (offset.top + el_height)) / pa_height*100
			};
			
			if (po_from === 't/l' || po_from === 't/r') {
				$('[name="element_idle_top"]').val(position.top);
				$('[name="element_idle_bottom"]').val('');
				settings.styles.idle_state.top    = position.top;
				settings.styles.idle_state.bottom = '';
			} else {
				$('[name="element_idle_top"]').val('');
				$('[name="element_idle_bottom"]').val(position.bottom);
				settings.styles.idle_state.top    = '';
				settings.styles.idle_state.bottom = position.bottom;
			}
					
			if (po_from === 't/l' || po_from === 'b/l') {
				$('[name="element_idle_left"]').val(position.left);
				$('[name="element_idle_right"]').val('');
				settings.styles.idle_state.left  = position.left;
				settings.styles.idle_state.right = '';
			} else {
				$('[name="element_idle_left"]').val('');
				$('[name="element_idle_right"]').val(position.right);
				settings.styles.idle_state.left  = '';
				settings.styles.idle_state.right = position.right;
			}
				
			settings.styles.idle_state['positions-from'] = po_from;
			settings.styles.idle_state['positions-unit'] = po_unit;
			
		});
		
		/*** change width values (units: px or %) ***/
		$(document).on('change', '[name="element_idle_width-unit"], [name="element_idle_height-unit"]', function() {
			
			var width_unit  = $('[name="element_idle_width-unit"]').val(),
				height_unit = $('[name="element_idle_height-unit"]').val(),
				$element    = $('.tg-element-draggable.'+tg_element_name),
				settings    = $element.data('settings'),
				el_bound    = get_element_size($element),
				pa_bound    = get_element_size($element.parent()),
				el_width    = el_bound.width,
				el_height   = el_bound.height,
				pa_width    = (settings.styles.idle_state.position === 'relative') ? pa_bound.innerWidth : pa_bound.width,
				pa_height   = (settings.styles.idle_state.position === 'relative') ? pa_bound.innerHeight : pa_bound.height;

			var $width_input = $('[name="element_idle_width"]');
			if ($(this).attr('name') === 'element_idle_width-unit' && $width_input.val()) {
				// calculate width in percent
				if (width_unit === '%') {
					el_width = el_width/pa_width*100;
				}
				$width_input.val(el_width);
				settings.styles.idle_state.width  = el_width;
			}
			
			var $height_input = $('[name="element_idle_height"]');
			if ($(this).attr('name') === 'element_idle_height-unit' && $height_input.val()) {
				// calculate height in percent
				if (height_unit === '%') {
					el_height = el_height/pa_height*100;
				}
				$height_input.val(el_height);
				settings.styles.idle_state.height = el_height;
			}
		
		});

		$(document).on('click', '.tg-toogle-transform', function() {
			$(this).next().slideToggle(300);
		});
		
		/*** update fields transform from animation type ***/
		$(document).on('change input', '.tg-transform-fields input, .tg-transform-fields select', tg_debounce(function() {

			var $tab = $(this).closest('.tomb-tab-content'),
				animation,
				$emptyFields = $tab.find('.tg-transform-fields input').filter(function() {
					return $.trim(this.value) === '';
				});

			if ($emptyFields.length == 15) {
				animation = '';
			} else {
				animation = 'custom';	
			}
			
			$tab.find('[name*="_animation_name"]').val(animation);
			update_select($tab);
			
		}, 20));
		
		/*** update fields transform from animation type ***/
		$(document).on('change input', '[name*="_animation_name"]', function() {
			
			var name   = $(this).val(),
				prefix = $(this).closest('[data-prefix]').data('prefix'),
				$tab   = $(this).closest('.tomb-tab-content');
			
			$tab.find('.tg-transform-fields input, .tg-transform-fields select').each(function(index, element) {
                var $this = $(this),
					type  = $this.attr('name').replace(prefix, ''),
					value = (tg_anim.hasOwnProperty(name) && $.isNumeric(tg_anim[name].transform[type])) ? tg_anim[name].transform[type] : '';
				value = (type === 'translate-unit' && !value) ? 'px' : value;
				$this.val(value);
            });

			update_select($tab);
			
		});
		
		/*** highlight layers ***/
		$(document).on('mouseenter', '[data-highlight]', function() {
			if (!$('.tg-skin-build-inner.tg-item-preview').length) {
				$('.tg-skin-build-inner').find('.'+$(this).data('highlight')).append($('<div class="tg-layer-highlight"></div>'));
			}
		}).on('mouseleave', '[data-highlight]', function() {
			if (!$('.tg-skin-build-inner.tg-item-preview').length) {
				$('.tg-skin-build-inner').find('.'+$(this).data('highlight')).find('.tg-layer-highlight').remove();
			}
		});

		/*** apply styles to seletect element ***/
		$(document).on('change', '.tg-component-style-properties [name="element_idle_opacity"], .tg-component-style-properties [name="element_hover_opacity"]', tg_debounce(function(e) {
			style_change($(this));
		}, 20)).on('change', '.tg-component-style-properties .tomb-image', tg_debounce(function(e) {
			style_change($(this));
		}, 20)).on('input', '.tg-component-style-properties input, .tg-component-style-properties textarea', tg_debounce(function(e) {
			style_change($(this));
		}, 20)).on('change', '.tg-component-style-properties select, .tg-component-style-properties .tomb-checkbox, .tg-component-style-properties .tg-important-rule input', tg_debounce(function(e) {
			style_change($(this));
		}, 20));
		
		/*** Change styles when hover state is changed  ***/
		$(document).on('change', '[name="is_hover"]', tg_debounce(function() {
			style_change($('[name="element_hover_opacity"]'));
		}, 20));
		
		/*** save on element settings  ***/
		$(document).on('input change', '.tg-panel-element select, .tg-panel-element input, .tg-panel-element textarea', tg_debounce(function() {
			save_element_settings();
		}, 20));
		
		/*** update element class name select list  ***/
		$(document).on('input change', '.tg-panel-element .tg-component-sources [name]', tg_debounce(function() {
			format_element();
			update_element_list();
		}, 20));
		
		/*** update element class name select list  ***/
		$(document).on('change', '.tg-panel-element .tg-component-action [name="type"]', tg_debounce(function() {
			TG_element_color('.'+tg_element_name, $('.tg-element-selected').data('settings'));
		}, 20));
		
		/*** apply global css ***/
		$('[name="global_css"]').on('input change', tg_debounce(function() {
			process_global_css($(this).val());
		}, 20));
		
		/*** process element animation ***/
		$('.tg-element-animation select, .tg-element-animation input').on('input change', tg_debounce(function() {
			if ($('.tg-skin-build-inner.tg-item-preview').length) {
				pre_process_animation('element',tg_element_name);
			}
		}, 20));
		
		/*** process media/overlay animation ***/
		$('.tg-tab-media-animations select, .tg-tab-media-animations input').on('input change', tg_debounce(function() {
			if ($('.tg-skin-build-inner.tg-item-preview').length) {
				var prefix = $(this).closest('[data-prefix]').data('prefix').slice(0,-1);
				var element = $(this).closest('[data-settings]').data('settings');
				pre_process_animation(prefix, element);
			}
		}, 20));
				
		/*** hide/show content base on current skin style (masonry/grid) ***/
		$('select[name="skin_style"], [name="media_content"]').on('change', function() {
			check_skin_style();
			check_item_size();
			setTimeout(function(){ 
				rulersOffset();
			}, 0);
		});
		
		/*** Overlay position type ***/
		$('select[name="overlay_type"]').on('change', function() {
			check_overlay_type();
		});
		
		/*** item content holder position for masonry style ***/
		$('select[name="content_position"]').on('change', function() {
			check_content_position();
			setTimeout(function(){ 
				rulersOffset();
			}, 0);
		});
		
		/*** item column/row number ***/
		$('[name="skin_col"], [name="skin_row"], [name="item_x_ratio"], [name="item_y_ratio"]').on('input change', function() {
			check_item_size();
			rulersOffset();
		});
		

		/*** Add element in skin ***/
		$(document).on('click', '.tg-add-element', function(){
			
			var $custom  = $(this).closest('.tg-element-holder').find('.tg-element-custom'),
				$element = $custom.clone(),
				slug     = $custom.data('slug'),
				settings = (TG_skin_elements[slug]) ? JSON.parse(TG_skin_elements[slug]) : '';

			if (settings) {
				
				var name = 'tg-element-' + generate_unique_id();
				
				$element.data('settings', settings)
						.data('name', name)
						.removeAttr('data-slug').data('slug', null)
						.removeClass('tg-element-custom')
						.addClass('tg-element-draggable tg-element-init '+name);
				
				$element.append('<div class="tg-element-helper"></div>');
				
				var absolute = absolute_element($element),
					not_in   = (absolute) ? '[data-item-area="media-holder-top"], [data-item-area="media-holder-center"], [data-item-area="media-holder-bottom"]' : '[data-item-area="media-holder"]';
	
				if (absolute) {
					$('.tg-skin-build-inner [data-item-area]:not('+not_in+'):visible').first().append($element);
				} else {
					$('.tg-skin-build-inner [data-item-area]:not('+not_in+'):visible').first().prepend($element);
				}
	
				if (settings) {
					style_change(name, settings.styles.idle_state, 'idle_state');
					if (settings.styles.is_hover) {
						style_change(name, settings.styles.hover_state, 'hover_state');
					}
				}
	
				update_element_list();
				check_dropped_element();
				$('.tg-area-droppable').sortable('refresh');
				$element.find('.tg-element-helper').resizable(resizable_options);
				
				skin_was_modified = true;
			
			} else {
				alert('Sorry, an unknow error occurs while adding the element');
			}

		});
		
		/*** Remove element in skin ***/
		$('#tg-element-remove').on('click', function() {
			
			var result  = confirm(tg_admin_global_var.box_messages.tg_delete_element.message);
			if (!result) {
				return false;
			}
			
			var el = $('.tg-panel-skin .tg-element-draggable.'+tg_element_name),
				index = $('.tg-element-init').index(el);
			
			el.remove();
			update_element_list();
			check_dropped_element();
			
			if (!$('.tg-panel-skin .tg-element-draggable').length) {
				$('.tg-panel-element').removeClass('tg-visible');
			}
			
			$('.tg-panel-skin style[class="'+tg_element_name+'"]').remove();
			
			index = (index-1 < 0) ? 0 : index-1;
			$('.tg-panel-skin .tg-element-draggable.tg-element-init').eq(index).trigger('click');
			
			skin_was_modified = true;
			
		});
		
		/*** Clone element in skin ***/
		$('#tg-element-clone').on('click', function() {
			
			generate_unique_id();
			
			var $selected_element = $('.tg-element-draggable.tg-element-selected'),
				$cloned_element   = $selected_element.clone(true).removeClass(tg_element_name+' tg-element-selected'),
				element_settings  = $selected_element.data('settings');
			
			tg_element_name = 'tg-element-'+tg_element_id;
			$cloned_element.addClass(tg_element_name).data('name', tg_element_name).data('settings', element_settings);
			$cloned_element.insertAfter($selected_element);
			style_change(tg_element_name, element_settings.styles.idle_state, 'idle_state');
			if (element_settings.styles.is_hover) {
				style_change(tg_element_name, element_settings.styles.hover_state, 'hover_state');
			}

			select_element($cloned_element);
			
			// init ui resizable
			$cloned_element.find('.tg-element-helper').removeData('uiResizable')
				.removeClass('ui-resizable').html('')
				.resizable(resizable_options);
				
			skin_was_modified = true;

		});

		/*** assign icon ***/
		$('.tg-icons-list i').on('click', function() {
			var value  = $(this).attr('class');
			var $input = $(this).closest('.tg-icons-popup').data('input');
			value = (!$(this).hasClass('tg-icon-selected')) ? value : '';
			$input.prev('i').attr('class', value);
			$('.tg-icons-popup').removeClass('tg-icons-popup-open');
			$input.closest('.tg-icon-holder').removeClass('tg-icon-is-open');
			$input.val(value).trigger('change');
		});
		
		/*** close icons popup ***/
		$(document).on('click', function(e) {
			if (!$(e.target).is('.tg-icons-popup') && !$(e.target).is('.tg-icon-holder') && !$(e.target).is('.tg-icon-holder i') && !$(e.target).is('.tg-icons-search')) {
				if ($('.tg-icons-popup').is(':visible')) {
					$('.tg-icons-popup').removeClass('tg-icons-popup-open');
					$('.tg-icon-holder').removeClass('tg-icon-is-open');
				}
			}
		});
		
		/*** open icons popup ***/
		$('.tg-icon-holder').on('click', function() {
			
			var $this = $(this),
				pos   = $this.offset(),
				value = $this.find('input').val();
			
			$('.tg-icons-search').val('').trigger('change');
			$('.tg-icons-list').scrollTop(0);
			$('.tg-icons-popup i').removeClass('tg-icon-selected');
			$('.tg-icons-popup').css({left: pos.left, top: pos.top+58 -$(document).scrollTop()});
			$('.tg-icons-popup').data('input', $this.find('input'));
			if ($this.hasClass('tg-icon-is-open')) {
				$('.tg-icons-popup').removeClass('tg-icons-popup-open');
				$this.removeClass('tg-icon-is-open');
			} else {
				$('.tg-icons-popup').addClass('tg-icons-popup-open');
				$('.tg-icon-holder').removeClass('tg-icon-is-open');
				$this.addClass('tg-icon-is-open');
			}
			if (value) {
				$('.tg-icons-popup i.'+value).addClass('tg-icon-selected');
				var scrollTop = $('.tg-icon-selected').position().top;
				$('.tg-icons-list').scrollTop((scrollTop) ? scrollTop : 0);
			}
			
		});
		
		$('.tg-loading-editor').addClass('tg-hidden');
		
		/** search icons in list ***/
		$(document).on('keyup change input', '.tg-icons-search', function() {
			var val = $(this).val();
			$('.tg-icons-list i').each(function() {
				var $this = $(this);
				var name  = $this.attr('class');
				if (name.toLowerCase().indexOf(val) >= 0) {
					$this.show();
				} else {
					$this.hide();
				}
			});
		});
		
		$(window).on('resize', function() {
			$('.tg-icons-popup').removeClass('tg-icons-popup-open');
		});
		
		
	});

})(jQuery);