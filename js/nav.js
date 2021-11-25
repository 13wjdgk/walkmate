// 로그인 유무에 따라 nav 바 변경 write 김가은 
const session=async()=>{
    const account = await axios.get("../php/account/checkSession.php"); //session 확인 php
    console.log("id="+account.data[0].real_id); //세션이 유효할 시, 사용자 id가 출력됨
    if(account.data[0].real_id){ // 사용자 id 가 있으면 html에 마이페이지,로그아웃을 nav바에 뜨게 함
    $('.menu' ).append('<a class="menu_a" href="./mypage.html">마이페이지</a>/<a class="menu_a" href="../php/account/logout.php">로그아웃</a>' );}
    else{// 사용자 id 가 없으면 html에 로그인, 회원가입 nav바에 뜨게 함
        $('.menu' ).append('<a class="menu_a" href="./login.html">로그인</a>/<a class="menu_a" href="./member.html">회원가입</a>');
    }
};
session();

const checkLogin = async() => {
    const account = await axios.get("../php/account/checkSession.php");
    console.log(account.data[0].real_id);
    if(account.data[0].real_id){
        location.href="../html/create.html";
    }
    else {
        alert("로그인 후 이용 가능한 서비스입니다.");
        location.href="../html/login.html";
    }
}