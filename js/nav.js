// 로그인 유무에 따라 nav 바 변경 write 김가은 
const session=async()=>{
    const account = await axios.get("../php/account/checkSession.php");
    console.log(account.data[0].real_id);
    if(account.data[0].real_id){
    $('.menu' ).append('<a class="menu_a" href="./mypage.html">마이페이지</a>/<a class="menu_a" href="../php/account/logout.php">로그아웃</a>' );}
    else{
        $('.menu' ).append('<a class="menu_a" href="./login.html">로그인</a>/<a class="menu_a" href="./member.html">회원가입</a>');
    }
};
session();
//로그인이 되어 있는지 확인한 후 로그인이 되지 않았으면 산책 구하기 페이지를 띄우지 않고 경고창을 띄움
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