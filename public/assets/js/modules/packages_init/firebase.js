function leftTrim(str, v){
    if(str[0] == v)
        str = str.substring(1);
    return str;
}
class Firebase {

    sendingResult = null;
    constructor() {
        this.firebaseConfig = {
            apiKey: "AIzaSyC9hDiuhNvpQkImezKOzIsVOOwFWKRGSeA",
            authDomain: "bunyan-86e3b.firebaseapp.com",
            projectId: "bunyan-86e3b",
            storageBucket: "bunyan-86e3b.appspot.com",
            messagingSenderId: "68918443742",
            appId: "1:68918443742:web:65da880da0c9632f620c4f",
            measurementId: "G-ZM03S8LTSJ"
        };
        firebase.initializeApp(this.firebaseConfig);
    }

    render() {
        window.recaptchaVerifier=new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            size: "invisible"
        });
        recaptchaVerifier.render();
    }

     SendCode(phoneNumber) {
        console.log(phoneNumber)
        var number = `+962${leftTrim(phoneNumber, "0")}`;
        console.log(number);
        var obj = this;
        return new Promise((resolve ,reject) => {
            firebase.auth().signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
                window.confirmationResult=confirmationResult;
                 obj.sendingResult = confirmationResult;
                resolve(true);
            }).catch(function (error) {
                console.log(error);
                return resolve(false);
            });
        });
    }

     VerifyCode(code) {
        console.log(code);
         return new Promise(resolve => {
             this.sendingResult.confirm(code).then(function (result) {
                 var user=result.user;
                 // console.log("success");
                 firebase.auth().currentUser.getIdToken().then(token => {
                     console.log(token);
                     resolve (token);
                 })

             }).catch(function (error) {
                 console.log(`Error ${error}`);
                 resolve(false);
             });
         });
    }


}
export default Firebase;
