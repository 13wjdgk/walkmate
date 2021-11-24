function getWalkKey() {

    var params = location.search.substr(location.search.indexOf("?") + 1);

    var sval = "";

    params = params.split("=");
    return params[1];

}
const deleteWalk = async () => {
    const walkKey = getWalkKey();
    if (walkKey) {
        try {
            const response = await axios.post("../php/walk/deleteWalk.php", {
                walkKey: walkKey,
            });
            if (response.data) {
                console.log(response.data);
            }

        }
        catch (error) {
            console.log(error);
        }
    }
}
const applyWalk = async () => {
    const walkKey = getWalkKey();
    if (walkKey) {
        try {
            const response = await axios.post("../php/walk/applyWalk.php", {
                walkKey: walkKey,
            });
            if (response.data) {
                console.log(response.data);
            }

        }
        catch (error) {
            console.log(error);
        }
    }
}

