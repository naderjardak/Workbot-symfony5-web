checked = 0;
changed = false;
function showHide(idInput, idCheck) {
if(document.getElementById(idCheck).checked) {
    checked++;
    document.getElementById(idInput).style.visibility = 'visible';
}else {
    checked--;
    document.getElementById(idInput).style.visibility = 'hidden';
    if(idInput != "inputDate") {
        document.getElementById(idInput).value = "";
    } else {
        if(document.getElementById("dateDebut") != null)
            document.getElementById("dateDebut").value = "";
        if(document.getElementById("dateFin") != null)
            document.getElementById("dateFin").value = "";
    }
}
if(checked >0) {
    document.getElementById("resetFilters").style.visibility = 'visible';
    document.getElementById("filterButton").style.visibility = 'visible';
} else {
    if(!changed)
        document.getElementById("resetFilters").style.visibility = 'hidden';
    document.getElementById("filterButton").style.visibility = 'hidden';

}
}

function resetFilters() {

    document.getElementById("inputCandidat").style.visibility = 'hidden'
    document.getElementById("inputCandidat").value = ''
    document.getElementById("filterCandidat").checked = false;
    document.getElementById("inputContrat").style.visibility = 'hidden'
    document.getElementById("inputContrat").value = ''
    document.getElementById("filterContrat").checked = false;
    document.getElementById("inputSalaire").style.visibility = 'hidden'
    document.getElementById("inputSalaire").value = ''
    document.getElementById("filterSalaire").checked = false;
    document.getElementById("inputDate").style.visibility = 'hidden'
    document.getElementById("inputDate").value = ''
    document.getElementById("filterDate").checked = false;
    document.getElementById("resetFilters").style.visibility = "hidden";
    document.getElementById("resetFilters").value = "";
    document.getElementById("filterButton").style.visibility = "hidden";
    document.getElementById("filterButton").value = "";
    if(changed) {
    document.getElementById("resetFilters").disabled = true;
    document.getElementById("filterButton").disabled = true;
    $.ajax({
        type: "POST",
        url: "http://localhost:8000/contrat/admin/filter",
        data: {candidat: "", salaire: 0, typeContrat: "", dateDebut: "", dateFin:""},
        success: function (data) {
            data = JSON.parse(data);
            data = data.contrats;
            changed = false;
            setData(data);

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus, XMLHttpRequest);
        }
    })
    }
}

function applyFilters() {
    let errors = false;
    let checkCandidat = document.getElementById("filterCandidat");
    let inputCandidat = document.getElementById("inputCandidat");
    inputCandidat.style.border = "";

   let checkContrat = document.getElementById("filterContrat");
    let inputContrat = document.getElementById("inputContrat");
    inputContrat.style.border =""

    let checkSalaire = document.getElementById("filterSalaire");
    let inputSalaire = document.getElementById("inputSalaire");
    inputSalaire.style.border =""

   let checkDate = document.getElementById("filterDate");
   let inputDateDebut = document.getElementById("dateDebut");
    inputDateDebut.style.border =""
   let inputDateFin = document.getElementById("dateFin");
    inputDateFin.style.border =""

   if(checkCandidat.checked && inputCandidat.value == "") {
       inputCandidat.style.border = "1px solid red";
       errors = true;
   }
    if(checkSalaire.checked) {
        if(isNaN(parseFloat(inputSalaire.value))){
            inputSalaire.style.border = "1px solid red";
            errors = true;
        }
    }
    if(checkDate.checked) {
        if(!(inputDateFin.value > inputDateDebut.value)){
        inputDateDebut.style.border = "1px solid red";
        inputDateFin.style.border = "1px solid red";
        errors = true;
        }
    }
    if(checkContrat.checked) {
        if(inputContrat.value == ""){
            inputContrat.style.border = "1px solid red";
            errors = true;
        }
    }
   if (errors)
       return;

    if(!checkSalaire.checked) {
        inputSalaire.value = 0;
    }
    if(!checkContrat.checked) {
        inputContrat.value = "";
    }
    document.getElementById("resetFilters").disabled = true;
    document.getElementById("filterButton").disabled = true;
    $.ajax({
        type: "POST",
        url: "http://localhost:8000/contrat/admin/filter",
        data: {candidat: inputCandidat.value, salaire: inputSalaire.value, typeContrat: inputContrat.value, dateDebut: inputDateDebut.value, dateFin:inputDateFin.value},
        success: function (data) {
            changed = true;
            data = JSON.parse(data) ;
            data = data.contrats;
            setData(data);

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            document.getElementById("resetFilters").disabled = true;
            document.getElementById("filterButton").disabled = true;
            console.log(textStatus, XMLHttpRequest);
        }
    })


}

function setData(data) {
    document.getElementById("tableBody").innerHTML = "";
    for(let i=0;i<data.length;i++) {
        let dateDebut =data[i].datedebut.date.substring(0, data[i].datedebut.date.indexOf(" "));

        let dateFin = data[i].datefin.date.substring(0, data[i].datefin.date.indexOf(" "));

        let dateCreation =data[i].datecreation.date.substring(0, data[i].datecreation.date.indexOf(" "));
        document.getElementById("tableBody").innerHTML+="" +
            "<tr>" +
            "<td>"+data[i].typecontrat+"</td>"+
            "<td>"+dateDebut+"</td>"+
            "<td>"+dateFin+"</td>"+
            "<td>"+data[i].salaire+"</td>"+
            "<td>"+dateCreation+"</td>"+
            "<td>"+data[i].nomcondidat+"</td>"+
            "<td>\n" +
            "                    <a href='../adminshow/"+data[i].id+"'>show</a>\n" +
            "                </td>"+
            "</tr>";
    }
    document.getElementById("resetFilters").disabled = false;
    document.getElementById("filterButton").disabled = false;
}