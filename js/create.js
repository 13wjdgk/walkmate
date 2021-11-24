//산책메이트 구하기 글 올리는 함수
const createWriting = async () => {
    // 글을 올리는데 필요한 변수들을 html에서 받아옴
    const title = document.querySelector(".title").value;
    const maxMemberCount = document.querySelector(".maxMemberCount").value;
    const description = document.querySelector(".description").value;
    const depTime = document.querySelector(".depTime").value;
    const depLatitude = document.getElementById('getAddress_Ma').value;
    const depLongitude = document.getElementById('getAddress_La').value;
    // 글을 올리는데 필요한 모든 정보들이 입력되었는가를 판단
    if (title && depTime && maxMemberCount && description&&depLongitude&&depLatitude) {
        //입력되었으면 산책 목록을 보여주는 페이지로 이동
        location.href ="./../html/list.html";
        try {
            // 받아온 정보들을 php 파일로 전송
            const response = await axios.post("../php/walk/writeWalk.php", {
                title: title,
                depLatitude: depLatitude,
                depLongitude: depLongitude,
                maxMemberCount: maxMemberCount,
                description: description,
                depTime: depTime,
            });
        }
        catch (error) {
            console.log(error);
        }
    }
}