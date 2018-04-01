window.onload = function() {

    var oDiv = document.getElementById("tab");

    var oLi = oDiv.getElementsByTagName("div")[0].getElementsByTagName("li");

    var aCon = oDiv.getElementsByTagName("div")[1].getElementsByTagName("div");

 var timer = null;
    for (var i = 0; i < oLi.length; i++) {
        oLi[i].index = i;
        oLi[i].onclick=function(){
			for(var i=0;i<oLi.length; i++){
				oLi[i].className="";
				aCon[i].style.display="none";
			}
			this.className="cur";
			aCon[this.index].style.display="block";
		}
    }
    
}