//this code handles the F5/Ctrl+F5/Ctrl+R

function disableF5(e) { 

    if ((e.which || e.keyCode) == 116)
        e.preventDefault();
    
    // stop ctrl+r
    if (e.ctrlKey && e.keyCode == 82){
        e.preventDefault();
        
    } 

    // stop ctrl+u
    if (e.ctrlKey && e.keyCode == 85){
        e.preventDefault();
        
    }
                    
};

$(document).on("keydown", disableF5);


// document.onkeydown = checkKeycode;
// function checkKeycode(e) {
//     var keycode;
    
//     if (window.event){
//         keycode = window.event.keyCode;
//     }else if (e){
//         keycode = e.which;
//     }
    

    
//     // Mozilla firefox
//     if ($.browser.mozilla) {
//         if (keycode == 116 || (e.ctrlKey && keycode == 82) || 
//                               (e.ctrlKey && (keycode === 67 || keycode === 86 || keycode === 85 || keycode === 117))) {
//             if (e.preventDefault)
//             {
//                 e.preventDefault();
//                 e.stopPropagation();
//             }
//         }
//     } 
//     // IE
//     else if ($.browser.msie) {
//         if (keycode == 116 || (window.event.ctrlKey && keycode == 82) || 
//                               (window.event.ctrlKey && (keycode === 67 || keycode === 86 || keycode === 85 || keycode === 117))) {
//             window.event.returnValue = false;
//             window.event.keyCode = 0;
//             window.status = "Refresh is disabled";
//         }
//     }
//     // Chrome & Others
//     else {
//         if (keycode == 116 || (window.event.ctrlKey && keycode == 82) || 
//                               (window.event.ctrlKey && (keycode === 67 || keycode === 86 || keycode === 85 || keycode === 117))) {
//             window.event.returnValue = false;
//         }
//     }
        
    
// }

//end function