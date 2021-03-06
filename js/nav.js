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
//로그인이 되어 있는지 확인한 후 로그인이 되지 않았으면 산책 구하기 페이지를 띄우지 않고 경고창을 띄움 write 고수민
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

// Add by NamHyeok Kim
const getNow = () => {
    const now = new Date(Date.now());
    const month = now.getMonth() + 1;
    const arr = {
        year: now.getFullYear(),
        month: month > 9 ? month : '0' + month,
        date: now.getDate() > 9 ? now.getDate() : '0' + now.getDate(),
        hour: now.getHours() > 9 ? now.getHours() : '0' + now.getHours(),
        minute: now.getMinutes() > 9 ? now.getMinutes() : '0' + now.getMinutes(),
        second: now.getSeconds() > 9 ? now.getSeconds() : '0' + now.getSeconds()
    }

    const nowString = `${arr.year}-${arr.month}-${arr.date} ${arr.hour}:${arr.minute}:${arr.second}`;
    return nowString;
}