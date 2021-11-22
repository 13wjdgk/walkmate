// var select_walklist=document.getElementById("select_walklist");
// var value=select_walklist.options[select_walklist.selectedIndex].value;
// console.log(value);
//최근 등록된   confirmApplyWalk.php

const getApplyMywalk=async()=>{
    const list = await axios.post("../php/walk/confirmApplyWalk.php",{
        
    })
}
const getRecWalk=async()=>{
    const list = await axios.post("../php/walk/getWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: "2021-11-23 10:00:00"
    });
    console.log(list.data.walksCount);
    if(list.data.walksCount){
        for(var i=0;i<list.data.walksCount;i++){
            $('ul' ).append('<li><a href="detail.html"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
        }
}
//가까운 거리
const getNearWalk=async()=>{
    const list = await axios.post("../php/walk/getRecWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: "2021-11-23 10:00:00",
        limitDistance: 1.0
    });
    console.log(list);
    if(list.data.walksCount){
        for(var i=0;i<list.data.walksCount;i++){
            console.log(list.data.walks[i].title);
            $('ul' ).append('<li><a href="detail.html"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
        }
    }


// const value=select_walklist.options[select_walklist.selectedIndex].value;
// console.log(value);
if(false){
    getNearWalk();
}else{
    getRecWalk();
}


