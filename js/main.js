const session=async()=>{
    const account = await axios.get("../php/main.php");
    console.log(account.data);
    console.log(typeof account.data);
    if(typeof account.data == typeof 'hello'){
    $('.menu' ).append('<a class="menu_a" href="./mypage.html">마이페이지</a>/<a class="menu_a" href="../php/logout.php">로그아웃</a><a class="menu_a" href=""><img class="notice"src="../img/알림.png" alt="알림"></a>' );}
    else{
        $('.menu' ).append('<a class="menu_a" href="./login.html">로그인</a>/<a class="menu_a" href="./member.html">회원가입</a>');
    }
};
session();

