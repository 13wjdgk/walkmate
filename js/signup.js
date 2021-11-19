const signup=async()=>{
    const id=document.getElementById("user_name").value;
    const user_pw=document.getElementById("user_pw").value;
    const nickname=document.getElementById("nickname").value;
    const addr=document.getElementById("addr").value;
    const mail=document.getElementById("mail").value;
    const phone=document.getElementById("phone").value;
    const birth=document.getElementById("birth").value;
    let gender=document.querySelector('input[name="gift"]:checked').value; // 체크된 값(checked value)
    if(gender=="female"){
        gender=true;
    }else{gender=false;}
    // console.log(id);
    // console.log(user_pw);
    // console.log(nickname);
    // console.log(addr);
    // console.log(mail);
    // console.log(phone);
    // console.log(birth);
    // console.log(gender);
    if(id&&user_pw&&nickname&&addr&&mail&&phone&&birth&&(gender!=null)){
        console.log("데이터 들어옴");
        try{
            const account = await axios.post("../php/signup.php",{
                user_id:id,
                user_pw:user_pw,
                nickname:nickname,
                addr:addr,
                mail:mail,
                phone:phone,
                birth:birth,
                gender:gender
            });
            if(account.data){
                console.log(account.data);
            }else{ console.log("입력 실패");}
           
        }catch(error){
            console.log(error);
        }
    }else{
        console.log("데이터 안들어옴");
    }

};