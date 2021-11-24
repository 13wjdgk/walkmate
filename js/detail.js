// 삭제하기 버튼을 눌렀을 때 실행될 함수
const deleteWalk = async () => {
    //getWalkKey 함수를 통해 산책 글의 primary key값 받아오기
    const walkKey = getWalkKey();
    //받아온 값이 참이면 if문 내용 실행
    if (walkKey) {
        try {
            //walkKey를 주면 php 파일을 통해 해당 글을 삭제함
            const response = await axios.post("../php/walk/deleteWalk.php", {
                walkKey: walkKey,
            });
        }
        catch (error) {
            console.log(error);
        }
    }
}
// 신청하기 버튼을 눌렀을 때 실행될 함수
const applyWalk = async () => {
    //getWalkKey 함수를 통해 산책 글의 primary key값 받아오기
    const walkKey = getWalkKey();
    //받아온 값이 참이면 if문 내용 실행
    if (walkKey) {
        try {
            //walkKey를 주면 php 파일을 통해 산책을 신청할 수 있음
            const response = await axios.post("../php/walk/applyWalk.php", {
                walkKey: walkKey,
            });
        }
        catch (error) {
            console.log(error);
        }
    }
}

