//mypage.js write 김가은
function chageLangSelect(){ 
    let select_walklist = document.getElementById("select_walklist"); // select element에서 선택된 option의 value가 저장된다. 
    let selectValue = select_walklist.options[select_walklist.selectedIndex].value; // select element에서 선택된 option의 text가 저장된다. 
    if(selectValue==="apply"){       // select가 신청한 게시물일 때
        $('ul *').remove();
        getApplyWalkList();
    }else if(selectValue==="host"){   // select가 작성한 게시물일  때
        $('ul *').remove();
        getHostWalkList();
    }else{                              // select가 신청 승인된 게시물일  때
        $('ul *').remove();
        getJoinWalkList();
    }
}
//내가 신청한 게시글
const getApplyWalkList=async()=>{
    const list = await axios.get("../php/walk/getApplyWalkList.php",{
    });
    if(list.data.walksCount){
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 :  '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>');};
                }
}
//승인하기 버튼 함수
const apply = (walkKey, userKey) => {
    axios.post("../php/walk/confirmApplyWalk.php", {
            walkKey: walkKey,
            confirmData : {
                userKey: userKey,
                isAccept: true
        }
    }).then((res) => {
        if(res.data.isSuccess) {
            alert("성공");
        } else {
            console.log(res.data);
        }
    })
    .catch((error) => {
        console.log(error.data);
    })
};

// 내가 작성한 게시물 리스트 가져오기 write 김남혁
const getHostWalkList=async()=>{
    const list = await axios.get("../php/walk/getHostWalkList.php",{
    });

    if(list.data.walksCount){           
        for(let i=0;i<list.data.walksCount;i++){
            const memberlist=list.data.walks[i].memberList;
            const applylist=list.data.walks[i].applyList;
            $('ul' ).append(`<li><a href="http://localhost/html/detail.html?walkKey=${list.data.walks[i].walkKey}"><p class="li_h">${list.data.walks[i].title}</p></a><p style="color: gray;">인원 : ${list.data.walks[i].maxMemberCount}명 날짜 : ${list.data.walks[i].depTime}</p><div id="reco${i}" class="reco"><h5>신청한 사람</h5></div></li>`);                        

            if(applylist) {
                for(let apply in applylist){  
                    $(`#reco${i}`).append('<div><h6>-'+applylist[apply].nickname+'</h6> &nbsp;&nbsp;&nbsp; <a href="#" onclick="apply('+list.data.walks[i].walkKey+','+applylist[apply].memberKey+');">승인하기</a></div>');
                }
            }

            $(`#reco${i}`).append('<h5>승인된 사람</h5>');

            if(memberlist) {
                for(let member in memberlist){   ;
                    $(`#reco${i}`).append('<div><h6>-'+memberlist[member].nickname+'</h6></div>'); 
                }
            }
        }            
    }
}

//승인된 게시글 가져오기 write 김가은
const getJoinWalkList=async()=>{
    const list = await axios.get("../php/walk/getJoinWalkList.php",{
    });
    if(list.data.walksCount){
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 :  '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>');
                    
            };
                }
}
getJoinWalkList();
