//signup.js write 김가은
const signup=async()=>{
    // addrPass에서 member.html의 kakao.maps.event.addListener 함수를 통해 클릭한 곳의 위도, 경도값을 가져옴
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

    //객체에서 위도,경도 값을 가져오는 kakao map 라이브러리에서 제공하는 함수 사용
    const addrLatitute = addr.getLat();
    const addrLongitude = addr.getLng();
    //회원가입에 필요한 모든 데이터가 들어왔으면 if문의 내용 실행
    if(id&&user_pw&&nickname&&addr&&mail&&phone&&birth&&(gender!=null)){
        try{
            //회원가입에 필요한 데이터를 php 파일로 넘겨줌
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
            //데이터를 넘기는 것에 성공했으면 if문의 내용 실행
            if(account.data){
                //회원가입에 성공하였으니 로그인 페이지로 넘어감
                self.location='./login.html';
            }else{ console.log("입력 실패");}
           
        }catch(error){
            console.log(error);
        }
    }else{
        console.log("데이터 안들어옴");
    }

};