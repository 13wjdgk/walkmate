//signup.js write 김가은
const signup=async()=>{
    var addr;
    addr = addrPass();
    const id=document.getElementById("user_name").value;
    const user_pw=document.getElementById("user_pw").value;
    const nickname=document.getElementById("nickname").value;
    const mail=document.getElementById("mail").value;
    const phone=document.getElementById("phone").value;
    const birth=document.getElementById("birth").value;
    let gender=document.querySelector('input[name="gift"]:checked').value; // 체크된 값(checked value)
    if(gender=="female"){
        gender=1;
    }else{gender=0;}
    const addrLatitute = addr.getLat();
    const addrLongitude = addr.getLng();
    if(id&&user_pw&&nickname&&addr&&mail&&phone&&birth&&(gender!=null)){
        
        console.log("데이터 들어옴");
        try{
            const account = await axios.post("../php/account/signup.php",{
                user_id:id,
                user_pw:user_pw,
                nickname:nickname,
                addrLatitute: addrLatitute,
                addrLongitude: addrLongitude,
                mail:mail,
                phone:phone,
                birth:birth,
                gender:gender
            });
            if(account.data){
                console.log(account.data);
                self.location='./login.html';
            }else{ console.log("입력 실패");}
           
        }catch(error){
            console.log(error);
        }
    }else{
        console.log("데이터 안들어옴");
    }

};