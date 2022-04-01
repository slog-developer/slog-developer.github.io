/**************************************************************************************
 * jQuery Paging 0.1.7
 * by composite (ukjinplant@msn.com)
 * http://composite.tistory.com
 * This project licensed under a MIT License.
 **************************************************************************************/;
(function($){
	//default properties.
	var a=/a/i,defs={
		item:'li',next:'<a href="#" class="page_btn next_v1"></a>',prev:'<a href="#" class="page_btn pre_v1"></a>',format:'{0}',
		itemClass:'',sideClass:'paging-side',currentClass:'active'
		,length:10,max:1,current:1,append:false
		,href:'',event:true,first:'<a href="#" class="page_btn pre_v2"></a>',last:'<a href="#" class="page_btn next_v2"></a>'
	},format=function(str){
		var arg=arguments;
		return str.replace(/\{(\d+)\}/g,function(m,d){
			if(+d<0) return m;
			else return arg[+d+1]||"";
		});
	},item,make=function(op,page,cls,str){
		//item=document.createElement(op.item);
		item=document.createElement(op.item);
		if (cls == "active")
		{
			item.className=cls;
		}
		item.innerHTML=format(str,page,op.length,op.start,op.end,op.start-1,op.end+1,op.max);
		if(a.test(op.item)) item.href=format(op.href,page);
		if(op.event){
			$(item).bind('click',function(e){
				var fired=true;
				if($.isFunction(op.onclick)) fired=op.onclick.call(item,e,page,op);
				if(fired==undefined||fired)
					op.origin.paging($.extend({},op,{current:page}));
				return fired;
			}).appendTo(op.origin);
			//bind event for each elements.
			var ev='on';
			switch(str){
				case op.prev:ev+='prev';break;
				case op.next:ev+='next';break;
				case op.first:ev+='first';break;
				case op.last:ev+='last';break;
				default:ev+='item';break;
			}
			if($.isFunction(op[ev])) op[ev].call(item,page,op);
		}
		return item;
	};

	$.fn.paging=function(op){
		op=$.extend({origin:this},defs,op||{});//this.html('');
		if(op.max<1) op.max=1; if(op.current<1) op.current=1;
		op.start=Math.floor((op.current-1)/op.length)*op.length+1;
		op.end=op.start-1+op.length;
		if(op.end>op.max) op.end=op.max;
		//if(!op.append) this.empty();
		if(this.children('.paging-side').length) this.empty(); //추가 - 페이징을 구성하는 element가 있을경우 지우고 다시 구성
		//prev button
		if(op.first!==false) make(op,1,op.sideClass,op.first);
		if(op.current>op.length){
			make(op,op.start-1,op.sideClass,op.prev);
		}else{
			make(op,op.start,op.sideClass,op.prev);
		}
		//pages button
		for(var i=op.start;i<=op.end;i++)
			make(op,i,(i==op.current?''+op.currentClass:''),"<a>"+op.format+"</a>");
		//next button
		if(op.current<=Math.floor(op.max/op.length)*op.length && op.max>op.end){
			make(op,op.end+1,op.sideClass,op.next);
		}else{
			make(op,op.end,op.sideClass,op.next);
		}
		if(op.last!==false) make(op,op.max,op.sideClass,op.last);
		//last button
	};
})(jQuery);