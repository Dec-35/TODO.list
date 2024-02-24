document.getElementById("valInput").addEventListener("keypress", function (event) {
    if (event.keyCode == 13) {
        entrerVal(document.getElementById("valInput").value);
    }
});


function entrerVal(value) {
  var content = value
  if(content != ""){
    var p = document.createElement("p");
  p.textContent = content;
  p.setAttribute("onclick", "deleteMoi(this)");
  p.setAttribute("class", "aSuppr");
  document.getElementById("listes").append(p);
  document.getElementById("mainContainerContent").classList.add("active");

  document.getElementById("valInput").value = "";
  }
  
}

function saveLists(){

    var listArray = new Array
    var listeASuppr = document.getElementsByClassName("aSuppr");
    console.log(listeASuppr)
    Array.from(listeASuppr).forEach((elem) => {
        listArray.push(elem.textContent)
    })
    var valeur = JSON.stringify(Object.assign({},listArray))
    document.getElementById('hiddenInput').value = valeur
}



function suppr() {
  var listeASuppr = document.getElementsByClassName("aSuppr");
  
  Array.from(listeASuppr).forEach((elem, index) => {
    setTimeout(function(){
      elem.animate([
      {transform:"translateX(0)"},
      {transform:"translateX(-150vw)"}
      ],{
        duration: 500,
        fill : "forwards",
        timing : "ease-in"
      
      });
    
      setTimeout(function(){
        elem.remove();
        
      },400);
      document.getElementById("mainContainerContent").classList.remove("active");

    }, index * 150)
    
    
  });
  
  
}

function deleteMoi(elem) {
  var mainContainer = document.getElementById("mainContainerContent");
  var listeASuppr = document.getElementsByClassName("aSuppr");

  if(Array.from(listeASuppr).length <= 1){
    mainContainer.classList.remove("active");
  }
  elem.animate([
    {transform:"translateX(0)"},
    {transform:"translateX(-150vw)"}
    ],{
    duration: 500,
    fill : "forwards",
    timing : "ease-in"
    
    });

    setTimeout(function(){
    elem.remove()
    },400);
}