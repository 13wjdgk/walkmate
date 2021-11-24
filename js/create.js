const createWriting = async () => {
    const title = document.querySelector(".title").value;
    const maxMemberCount = document.querySelector(".maxMemberCount").value;
    const description = document.querySelector(".description").value;
    const depTime = document.querySelector(".depTime").value;
    const depLatitude = document.getElementById('getAddress_Ma').value;
    const depLongitude = document.getElementById('getAddress_La').value;
    if (title && depTime && maxMemberCount && description&&depLongitude&&depLatitude) {
        location.href ="./../html/list.html";
        console.log(title);
        console.log(maxMemberCount);
        console.log(description);
        console.log(depTime);
        console.log(description);
        console.log(depLongitude);
        console.log(depLatitude);
        try {
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

