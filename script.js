const imgpa = document.getElementsByClassName("pt");
const imgpts = document.getElementsByClassName("pts");
const inputs = document.getElementById("inputs");
const lowerf = document.getElementById("lowerf");
const lowerfbtni = document.getElementById("lowerfbtni");
const lowerfbtnii = document.getElementById("lowerfbtnii");
const alrnew = document.getElementById("alrnew");
const forml = document.getElementById("forml");
const vercontainer = document.getElementById("verifInputsContainer");
const upform = document.getElementById("upform");
const image = document.getElementById("logo");
const image2 = document.getElementById("logos");
const image2cont = document.getElementById("logo2cont");
const logocont = document.getElementById("logocont");
const container = document.getElementById("container");
const lowercont = document.getElementById("lowercont");
const btnsin = document.getElementById("btn-sin");
const suemail = document.getElementById("suemail");
const passDiv = document.getElementById("passDiv");
const idDiv = document.getElementById("idDiv");
const subbut = document.getElementById("subbut");

startpass();
startid();
var logoAnimation;
const test = document.createElement("SVG");
var animationcanceled;
animationcanceled = true;

var bbox = image.getBBox();

image.setAttribute(
  "viewBox",
  `${bbox.x} ${bbox.y} ${bbox.width} ${bbox.height}`
);

function testing() {
  patharrayl = Array.from(imgpts);

  for (var i = 0; i < patharrayl.length; i++) {
    var element = patharrayl[i];

    element.style.display = "block";
    element.style.animation = "test 3s 1 forwards";
  }
  var bbox = image.getBBox();

  image.setAttribute(
    "viewBox",
    `${bbox.x} ${bbox.y} ${bbox.width} ${bbox.height}`
  );
  var bbox2 = image2.getBBox();

  image2.setAttribute(
    "viewBox",
    `${bbox2.x} ${bbox2.y} ${bbox2.width} ${bbox2.height}`
  );
}

function revtesting() {
  image2.style.opacity = "0";
  setTimeout(() => {
    image2cont.style.width = "0";
  }, 100);
  var bbox = image.getBBox();

  image.setAttribute(
    "viewBox",
    `${bbox.x} ${bbox.y} ${bbox.width} ${bbox.height}`
  );
  var bbox2 = image2.getBBox();

  image2.setAttribute(
    "viewBox",
    `${bbox2.x} ${bbox2.y} ${bbox2.width} ${bbox2.height}`
  );
}

function myEndFunction() {
  logocont.style.animation = "back 2s 1 forwards";
  lowercont.style.display = "flex";
  container.style.gap = "15%";
  image.data = "Images/logsssso 1.svg ";
  btnsin.style.display = "block";
  btnsin.style.color = "transparent";
  btnsin.style.padding = "0";

  setTimeout(function () {
    paddinganstart(btnsin);
  }, 250);
  lowercont.style.display = "flex";

  image2cont.style.display = "block";
  testing();
}

function btnmo() {
  btnsin.style.backgroundColor = "rgb(181, 19, 19)";
}

function btnmou() {
  btnsin.style.backgroundColor = "rgb(142, 5, 5)";
}

setTimeout(myEndFunction, 4000);
patharray = Array.from(imgpa);
setInterval(() => {}, 1);

function beforesecond() {
  revtesting();
  setTimeout(secondfunction, 100);
}

function secondfunction() {
 if (window.innerWidth <= 480) {
    logocont.style.marginTop = "50px";
  } else if (animationcanceled) {
    logocont.style.marginTop = "0px";
  }
  inputs.style.display = "block";
  upform.style.display = "block";
  lowerf.style.display = "block";
  lowercont.style.padding = "2% 3%";

  handleResizeofform();
  let timeout;
  window.addEventListener("resize", function () {
    // Clear the previous timeout
    clearTimeout(timeout);

    // Set a new timeout
    timeout = setTimeout(handleResizeofform, 500); // Adjust the delay as needed
  });
  //
  //
  //
  //
  function handleFormChange(mutationsList, observer) {
    handleResizeofform();
  }

  // Create a MutationObserver with the callback function
  const observer1 = new MutationObserver(handleFormChange);

  // Target the form you want to observe
  const targetNode1 = document.getElementById("inputs");

  // Configure the observer to watch for changes in child elements
  const config1 = { childList: true, subtree: true };

  // Start observing the target node for configured mutations
  observer1.observe(targetNode1, config1);

  //
  //
  //
  //
  function handleResizeofform() {
    setTimeout(() => {
      upform.style.maxHeight = upform.scrollHeight + "px";

      inputs.style.maxHeight = inputs.scrollHeight * 2 + "px";
      upform.style.margin = "1% 0 5% 0";

      subbut.style.marginTop = "15px";
      lowerf.style.maxHeight = lowerf.scrollHeight * 2 + "px"; //there is a lil problem here due to nowrapping but this is like a fix for it
      forml.style.gap = "5px";
    }, 500);
  }

  container.style.animation = "testy  forwards";
  lowercont.style.backgroundColor = "white";
  setInterval(() => {
    if (window.innerWidth <= 480) {
      cancelanimatelogo(image);
    } else if (animationcanceled) {
      animatelogo(image);
      animationcanceled = false;
    }
  }, 1);

  setInterval(() => {
    if (window.innerWidth <= 480) {
      image.style.width = "75px";
      lowercont.style.width = "80%";
      lowercont.style.backgroundColor = "transparent";
      container.style.justifyContent = "start";
    } else {
      image.style.width = "100px";
      lowercont.style.backgroundColor = "white";
      lowercont.style.width = "35%";

      container.style.justifyContent = "center";
    }
  }, 1);

  btnsin.style.backgroundColor = "rgb(142, 5, 5)";
  btnsin.style.color = "white";
  btnsin.addEventListener("mouseover", btnmo);
  btnsin.addEventListener("mouseout", btnmou);
  container.style.gap = "5%";
  lowerf.style.width = "100%";
}
function thirdfunction() {
  lowerf.style.width = "0";
  setTimeout(function () {
    rtlanimation(lowerf);
  }, 1000);
  btnsin.style.color = "transparent";
  setTimeout(function () {
    paddinganim(btnsin);
  }, 250);
  setTimeout(function () {
    paddinganimrev(btnsin);
  }, 1000);

  suemail.style.height = "57.2px";
  setTimeout(function () {
    rtlanimation(suemail);
  }, 1000);

  setTimeout(afterthird, 1000);
}
function afterthird() {
  lowerfbtnii.style.display = "none";
  lowerfbtnii.setAttribute("onclick", "");
  lowerfbtni.textContent = "Sign in";
  btnsin.value = "Sign up";
  alrnew.textContent = "Already a member?";
  lowerfbtni.setAttribute("onclick", "fourthfunction()");
  addsuemailElements();
}
function fourthfunction() {
  lowerf.style.width = "0";
  setTimeout(function () {
    rtlanimation(lowerf);
  }, 1000);
  btnsin.style.color = "transparent";
  setTimeout(function () {
    paddinganim(btnsin);
  }, 250);
  setTimeout(function () {
    paddinganimrev(btnsin);
  }, 1000);
  suemail.style.width = "0";
  setTimeout(function () {
    dtuanimation(suemail);
  }, 800);

  setTimeout(afterfourth, 1000);
}
function afterfourth() {
  btnsin.value = "Sign in";
  lowerfbtni.textContent = "Sign up";
  alrnew.textContent = "New member?";
  lowerfbtnii.style.display = "block";
  lowerfbtnii.setAttribute("onclick", "fifthfunction()");
  lowerfbtni.setAttribute("onclick", "thirdfunction()");
  removesuemailElements();
}

function fifthfunction() {
  lowerf.style.width = "0";
  setTimeout(function () {
    rtlanimation(lowerf);
  }, 1000);
  btnsin.style.color = "transparent";
  setTimeout(function () {
    paddinganim(btnsin);
  }, 250);
  setTimeout(function () {
    paddinganimrev(btnsin);
  }, 1000);

  // vercontainer.style.height = "0";
  destroyidInputs();
  destroypassInputs();

  setTimeout(afterfifth, 1000);
}
function afterfifth() {
  lowerfbtnii.style.display = "none";
  lowerfbtnii.setAttribute("onclick", "");
  lowerfbtni.textContent = "Back";
  btnsin.value = "Send Pin";
  alrnew.textContent = "Return back?";
  lowerfbtni.setAttribute("onclick", "sixthfunction()");

  suemail.style.height = "57.2px";

  setTimeout(function () {
    addsuemailElements();
    rtlanimation(suemail);
  }, 500);

  // createVerificationfield();
  // setTimeout(fixheightprobver, 100);
}

function sixthfunction() {
  lowerf.style.width = "0";
  setTimeout(function () {
    rtlanimation(lowerf);
  }, 1000);
  btnsin.style.color = "transparent";
  setTimeout(function () {
    paddinganim(btnsin);
  }, 250);
  setTimeout(function () {
    paddinganimrev(btnsin);
  }, 1000);
  setTimeout(function () {
    dtuanimation(vercontainer);
  }, 800);
  suemail.style.width = "0";

  setTimeout(aftersixth, 1000);
}

// createpassfield();
// passDiv.style.height = "57.2px";
// setTimeout(function () {
//   rtlanimation(passDiv);
// }, 800);

function aftersixth() {
  btnsin.value = "Sign in";
  btnsin.setAttribute("onclick", "secondfunction()");
  lowerfbtni.textContent = "Sign up";
  alrnew.textContent = "New member?";
  lowerfbtnii.style.display = "block";
  lowerfbtnii.setAttribute("onclick", "fifthfunction()");

  lowerfbtni.setAttribute("onclick", "thirdfunction()");

  removesuemailElements();
  dtuanimation(suemail);
  fixidfield();
  setTimeout(fixpassfield, 300);
}

function rtlanimation(element) {
  element.style.width = "100%";
}
function dtuanimation(element) {
  element.style.height = "0";
}

function paddinganim(element) {
  element.style.padding = "0";
}

function paddinganimrev(element) {
  if (window.innerWidth <= 480) {
    element.style.padding = "8px 100px";
    element.style.color = "white";
  } else {
    element.style.padding = "8px 70px";
    element.style.color = "white";
  }
}
function paddinganstart(element) {
  if (window.innerWidth <= 480) {
    element.style.padding = "8px 100px";
    element.style.color = "white";
  } else {
    element.style.padding = "8px 70px";
    element.style.color = "rgb(142, 5, 5)";
  }
}

function removesuemailElements() {
  var suemaillabel = suemail.querySelector("label");
  var suemaillineBreak = suemail.querySelector("br");
  var suemailinput = suemail.querySelector("input");

  suemail.removeChild(suemaillabel);
  suemail.removeChild(suemaillineBreak);
  suemail.removeChild(suemailinput);
}

function addsuemailElements() {
  var suemaillabel = document.createElement("label");
  suemaillabel.textContent = "Email";
  var suemaillineBreak = document.createElement("br");
  var suemailinput = document.createElement("input");
  suemailinput.name = "Email";
  suemailinput.type = "email";
  suemailinput.placeholder = "Enter your E-mail";
  suemailinput.required = true;

  var suemail = document.getElementById("suemail");

  suemail.appendChild(suemaillabel);
  suemail.appendChild(suemaillineBreak);
  suemail.appendChild(suemailinput);
}
if (window.innerWidth <= 480) {
  element.style.color = "white";
} else {
  element.style.color = "rgb(142, 5, 5)";
}

function animatelogo(element) {
  var keyframes = new KeyframeEffect(
    element,
    [
      { transform: "translateY(0)" },
      { transform: "translateY(-25px)" },
      { transform: "translateY(0px)" },
    ],
    { duration: 3000, iterations: Infinity }
  );

  logoAnimation = new Animation(keyframes);

  logoAnimation.play();
}

function cancelanimatelogo(element) {
  if (logoAnimation) {
    logoAnimation.cancel();
    animationcanceled = true;
  }
}

function createpassfield() {
  var passInpStyles = document.createElement("div");
  passInpStyles.setAttribute("id", "passInpStyles");

  // Create the label
  var label = document.createElement("label");
  label.innerHTML = "Password";
  label.setAttribute("for", "passInput");

  // Create the password input
  var passInput = document.createElement("input");
  passInput.setAttribute("id", "passInput");
  passInput.setAttribute("name", "passcode");
  passInput.setAttribute("type", "password");
  passInput.placeholder = "Enter your Passcode";
  passInput.required = true;

  // Create the eye icon
  var togglePass = document.createElement("i");
  togglePass.setAttribute("id", "togglePass");
  togglePass.className = "fa-solid fa-eye-slash";

  // Append the elements to the parent div
  passDiv.appendChild(label);
  passDiv.appendChild(passInpStyles);
  passInpStyles.appendChild(passInput);
  passInpStyles.appendChild(togglePass);

  // Function to show and hide the password input field when clicking on eye icon
  let eyeIcon = document.getElementById("togglePass");
  eyeIcon.addEventListener("click", function () {
    if (passInput.type === "password") {
      passInput.type = "text";
      eyeIcon.classList.add("fa-solid", "fa-eye");
    } else {
      passInput.type = "password";
      eyeIcon.classList.remove("fa-solid", "fa-eye");
      eyeIcon.classList.add("fa-solid", "fa-eye-slash");
    }
  });
}

function createIDField() {
  // Create a label element
  var label = document.createElement("label");
  label.innerHTML = "ID";
  label.setAttribute("for", "idnumber");
  idDiv.appendChild(label);

  // Create an input element
  var inputElement = document.createElement("input");

  // Set attributes for the input element
  inputElement.id = "idnumber";
  inputElement.name = "idnumber";
  inputElement.type = "number";
  inputElement.placeholder = "Enter your ID";
  inputElement.min = "0";
  inputElement.step = "any"; // Use "any" or any specific step value you prefer
  inputElement.required = true;

  // Append the input element to the div element
  idDiv.appendChild(inputElement);
}
function destroyidInputs() {
  // Remove all child elements (inputs)

  idDiv.style.width = "0";
  setTimeout(function () {
    dtuanimation(idDiv);
  }, 1000);

  setTimeout(() => {
    idDiv.innerHTML = " ";
  }, 1000);
}

function fixpassfield() {
  createpassfield();
  passDiv.style.height = "57.2px";
  setTimeout(function () {
    rtlanimation(passDiv);
  }, 300);
}


function fixidfield() {
  createIDField();
    idDiv.style.height = "57.2px";
  setTimeout(function () {
    rtlanimation(idDiv);
  }, 300);
}
function startpass() {
  passDiv.style.height = "57.2px";
  setTimeout(function () {
    rtlanimation(passDiv);
  }, 300);
}
function startid() {
  idDiv.style.height = "57.2px";
  setTimeout(function () {
    rtlanimation(idDiv);
  }, 300);
}

function destroypassInputs() {
  // Remove all child elements (inputs)

  passDiv.style.width = "0";
  setTimeout(function () {
    dtuanimation(passDiv);
  }, 1000);

  setTimeout(() => {
    passDiv.innerHTML = " ";
  }, 1000);
}

// function createVerificationfield() {
//   var input2 = document.createElement("label");
//   input2.innerHTML = "Enter Pin";
//   input2.setAttribute("class", "new-pass-label");
//   input2.setAttribute("id", "new-pass-label");
//   input2.setAttribute("for", "new-pass");
//   vercontainer.appendChild(input2);
//   var input = document.createElement("div");
//   input.setAttribute("class", "new-pass-div");
//   input.setAttribute("id", "new-pass-div");
//   input.setAttribute("name", "new-pass-div");
//   vercontainer.appendChild(input);

//   for (var i = 1; i <= 4; i++) {
//     var input3 = document.createElement("input");
//     input3.setAttribute("class", "verifinput");
//     input3.setAttribute("type", "text");
//     input3.setAttribute("name", "n" + i);
//     input3.setAttribute("maxlength", "1");
//     input3.required = true;

//     input.appendChild(input3);
//   }
// }

function fixheightprobver() {
  vercontainer.style.height = "69.6px";
}
function revfixheightprobver() {
  vercontainer.style.height = "0";
}

// Function to destroy verification code input fields
function destroyVerificationInputs() {
  // Remove all child elements (inputs)
  vercontainer.innerHTML = " ";
}

// the names for id:idnumber   password:passcode   email:Email
//for back-end team edit the text inside this 2 functions to the php files
