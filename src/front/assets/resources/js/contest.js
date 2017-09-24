var passwordDisplay = false;

$(".radio3").change(function(){
    if($('input:radio[name="openness"]:checked').val()==2 && passwordDisplay==false){
        $("#passwordDiv").removeClass('hidden');
        passwordDisplay=true;
    }else if($('input:radio[name="openness"]:checked').val()!=2 && passwordDisplay==true){
        $("#passwordDiv").addClass('hidden');
        passwordDisplay=false;
    }
})

$("#formSubmit").click(function(){
    alert("submit button clicked");
    $("#contestInfo").submit();
})