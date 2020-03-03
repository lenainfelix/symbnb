$('#add-image').click(function(){
    //recuperer le num de futur champ a creer
    let index = +$('#widgets-counter').val();
    
    console.log(index);
    //recupere le prototype a copier
    let template = $('#annonce_images').attr('data-prototype').replace(/__name__/g, index);
    // j'injecte le code dans la div
    $('#annonce_images').append(template);

    $('#widgets-counter').val(index+1);

    handleDeleteButtons();

}) 

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        let target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter(){
    let count = +$('#annonce_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();
