
//list js write 김가은
//페이지가 로드되면 chageLangSelect 함수 실행
window.onload = function() {
    chageLangSelect();
}
function chageLangSelect(){ 
    let select_walklist = document.getElementById("select_walklist"); // select element에서 선택된 option의 value가 저장된다. 
    let selectValue = select_walklist.options[select_walklist.selectedIndex].value; // select element에서 선택된 option의 text가 저장된다. 
    if(selectValue==="near"){ //select가 가까운 산책물일 때 리스트를 불러옴
        $('ul *').remove();
        getNearWalk();
    }else{                  //select가 최근 산책물일 때 리스트를 불러옴
        $('ul *').remove();

        getRecWalk();
    }
}

//최근 등록된 
const getRecWalk=async()=>{
    const list = await axios.post("../php/walk/getWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: getNow()
    });
    console.log(list.data.walksCount);
    if(list.data.walksCount){
        
        for(var i=0;i<list.data.walksCount;i++){
            // 받아온 객체마다 html에 출력
            $('ul' ).append('<li><a href="'+'./detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
        }
}
//가까운 거리
const getNearWalk=async()=>{
    const list = await axios.post("../php/walk/getRecWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: getNow(),
        limitDistance: 4.0
    });
    console.log(list);
    if(list.data.walksCount){
        for(var i=0;i<list.data.walksCount;i++){
            console.log(list.data.walks[i].title);
            // 받아온 객체마다 html에 출력
            $('ul' ).append('<li><a href="'+'./detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
    }
}