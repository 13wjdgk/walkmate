const session=async()=>{
    const account = await axios.get("../php/account/main.php");
    console.log(account.data);
    console.log(typeof account.data);
    if(account.data){
    $('.menu' ).append('<a class="menu_a" href="./mypage.html">마이페이지</a>/<a class="menu_a" href="../php/account/logout.php">로그아웃</a><a class="menu_a" href="">' );}
    else{
        $('.menu' ).append('<a class="menu_a" href="./login.html">로그인</a>/<a class="menu_a" href="./member.html">회원가입</a>');
    }
};
session();

