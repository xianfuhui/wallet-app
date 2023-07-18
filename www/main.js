window.addEventListener('load', function() {
    document.querySelector('input[type="file"]').addEventListener('change', function() {
        if (this.files && this.files[0]) {
        var img = document.querySelector('img');
        img.onload = () => {
            URL.revokeObjectURL(img.src); 
        }

        img.src = URL.createObjectURL(this.files[0]); 
    }   
    });
});

function GetDetail(str) {
    if (str.length == 0) {
        document.getElementById("first_name").value = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && 
                    this.status == 200) {
                
                var myObj = JSON.parse(this.responseText);
                  
                document.getElementById
                    ("fullname").value = myObj[0];
            }
        };

        xmlhttp.open("GET", "gfg.php?phonenumber=" + str, true);
          
        xmlhttp.send();
    }
}