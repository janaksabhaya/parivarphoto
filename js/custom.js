$(document).ready(function() {
    $('#saveimgbtn').click(function() {
        var imgName = $("#finalimg").attr('src');
        if (imgName != "images/bg.png") {
            var link = document.createElement('a');
            link.href = imgName;
            link.download = 'mypost.jpg';
            document.body.appendChild(link);
            link.click();
        } else {
            alert("Please first make your post");
        }
    });
});

$(document).ready(function() {
    $('#whatsapp_btn').click(function() {
        var idd = $("#idd").val();
        var imgName = $("#finalimg").attr('src');
        if (imgName != "images/bg.png") {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
              
                switch(idd) {
                case 1:
                    var text1 = "હું પરિવાર ના વિકાસ માં યોગદાન માટે જોડાયો છું ... ";
                    var text2 = "*શું તમે જોડાયા છો?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=1";
                    break;
                case 2:
                    var text1 = "મેં આ વર્ષે પરિવાર ની સમૃધ્ધિ માટે યોગદાન આપ્યું..";
                    var text2 = "*શું તમે આપ્યું?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=2";
                    break;
                case "texa-tyre":
                    var text1 = "મને વિશ્વાસ છે ટેકસા ટાયર ની સર્વીસ અને પ્રોડક્ટ ઉપર... તો મેં *Texa* ટાયરની મેમ્બરશીપ લીધી છે";
                    var text2 = "*શું તમે લીધી કે નહીં?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=texa-tyre";
                    break;
                case "hanol-gam":
                    var text1 = "હું તો હણોલ ગામ ના સ્નેહમિલનમાં જઈ રહયો છું...";
                    var text2 = "*તમે આવશો ને..?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=hanol-gam";
                    break;
                case "sultanpur-gam":
                    var text1 = "હું તો સુલતાનપુર ગામના સ્નેહમિલનમાં જઈ રહયો છું...";
                    var text2 = "*તમે આવશો ને..?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=sultanpur-gam";
                    break;
                case "mangalnath-aahir":
                    var text1 = "હું મંગળનાથ આહિર ભવન ના વિકાસ માં યોગદાન માટે જોડાયો છું...";
                    var text2 = "*તમે પણ જોડાઈ જાવ* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=mangalnath-aahir";
                    break;
                case "hanol-hpl":
                    var text1 = "હું તો હણોલ પ્રીમિયર લિગ - 8 માં રમવા માટે જઈ રહ્યો છું...";
                    var text2 = "*તમે આવશો ને..?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/index.php?id=hanol-hpl";
                    break;    
                default:
                    var text1 = "હું આપણા પરિવાર ના વિકાસ અને એકજુટતા માટે સ્નેહમિલન માં જરૂરથી જવાનો છું, ";
                    var text2 = "*શું તમે જવાના છો?* ";
                    var link = "👉🏻 " + "http://freefestivepost.com/makepost/";
                }

                
                var text3 = "ફોટો જોવા અહીં ક્લિક કરો ";
                var images = "👉🏻 " + "http://freefestivepost.com/makepost/" + imgName;
                var text4 = "*તમારો ફોટો બનાવવા અહીં ક્લિક કરો* ";

                //var url = $(this).attr("data-link");
                var message =
                    encodeURIComponent(text1) + " %0a %0a " +
                    encodeURIComponent(text2) + " %0a " +
                    encodeURIComponent(text3) + " %0a " +
                    encodeURIComponent(images) + " %0a " +
                    encodeURIComponent(text4) + " %0a " +
                    encodeURIComponent(link);
                var whatsapp_url = "whatsapp://send?text=" + message;
                window.location.href = whatsapp_url;
            } else {
                alert("Please use an Mobile Device to Share this Article");
            }
        } else {
            alert("Please first make your post");
        }
    });
});
