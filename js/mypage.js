function chageLangSelect(){ 
    let select_walklist = document.getElementById("select_walklist"); // select element에서 선택된 option의 value가 저장된다. 
    let selectValue = select_walklist.options[select_walklist.selectedIndex].value; // select element에서 선택된 option의 text가 저장된다. 
    console.log(selectValue);
    if(selectValue==="apply"){
        $('ul *').remove();
        console.log(selectValue);
        getApplyWalkList();
    }else if(selectValue==="host"){
        $('ul *').remove();
        console.log(selectValue);
        getHostWalkList();
    }else{
        $('ul *').remove();
        console.log(selectValue);
        getJoinWalkList();
    }
}
const getDetailURL ='http://localhost/html/detail.html?';
//최근 등록된   confirmApplyWalk.php
//내가 신청한 게시글
const getApplyWalkList=async()=>{
    const list = await axios.get("../php/walk/getApplyWalkList.php",{
    });
    if(list.data.walksCount){
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 :  '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>');};
                }
}
const getHostWalkList=async()=>{

    const list = await axios.get("../php/walk/getHostWalkList.php",{
    });
    
    if(list.data.walksCount){           
        const apply=async(walkKey,userKey)=>{
            const apply_post = await axios.post("../php/walk/confirmApply.php",{
                walkKey: walkKey,
                confirmData : {
                userKey: userKey,
                isAcccept: true
            }
            });
        };
        for(let i=0;i<list.data.walksCount;i++){
            const memberlist=list.data.walks[i].memberList;
            const applylist=list.data.walks[i].applyList;
            console.log(memberlist);
            $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p><div class=".reco"><h5>신청한 사람</h5></div></li>');    
            console.log(memberlist);                    
            if(memberlist) {
                for(let j=0;j<memberlist.length;j++){    
                    $('.reco' ).append('<div>'+`<h6>dfjslf${memberlist[0].nickname}</h6>` +`<a href="#" onclick="apply(${list.data.walks[i].walkKey},${list.data.walks[i].memberList[j].userKey});">승인하기</a></div>`);    
                }
            }
            $('.reco' ).append('<h5>승인된 사람</h5>');
            
            if(applylist) {
                for(let j=0;j<applylist.length;j++){    
                    $('.reco' ).append('<div><h6>-'+list.data.walks.applyList[j].nickname+'</h6></div>');    
                }
            }
                    
        }            
    }
}
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



