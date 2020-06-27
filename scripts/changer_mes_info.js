	function info(a){
					document.getElementById("AI").style.visibility="hidden";
					document.getElementById("AC").style.visibility="hidden";
					document.getElementById("AP").style.visibility="hidden";
					document.getElementById("DP").style.visibility="hidden";
					document.getElementById("DT").style.visibility="hidden";
					document.getElementById("DI").style.visibility="hidden";
					document.getElementById("RI").style.visibility="hidden";
					document.getElementById("RP").style.visibility="hidden";
					document.getElementById("AA").style.visibility="hidden";
		//			document.getElementById("BtnG").style.visibility="hidden";

					if(a===0){
						document.getElementById("AI").style.visibility="visible";
					}
					if(a===1){
						document.getElementById("AP").style.visibility="visible";
					}
					if(a===2){
						document.getElementById("DI").style.visibility="visible";
						document.getElementById("BTNGEN").style.visibility="hidden";
					}
					if(a===3){
						document.getElementById("DP").style.visibility="visible";
						document.getElementById("BTNGEN").style.visibility="hidden";
					}
					if(a===4){
						document.getElementById("DT").style.visibility="visible";
						document.getElementById("BTNGEN").style.visibility="hidden";
					}
					if(a===5){
						document.getElementById("RI").style.visibility="visible";
					}
					if(a===6){
						document.getElementById("RP").style.visibility="visible";
					}
					if(a===7){
						document.getElementById("AC").style.visibility="visible";

					}
					if(a===8){
						document.getElementById("AA").style.visibility="visible";
						document.getElementById("BTNGEN").style.visibility="visible";
					}
				}
