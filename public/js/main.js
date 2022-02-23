$(function(){

    $(".add").click(function() {
        let id = $(this).attr("id-product");
        //AJAX
        $.get("add_cart/" + id, function(nbProducts) {
            confirm("ajouter !")
            localStorage.setItem('items',JSON.stringify(nbProducts))
            $(".prod_nb").text(JSON.parse(localStorage.getItem('items')))
        });
    });

    // affichage du nombre d'article 
    if(localStorage.getItem('items') != null){
        $(".prod_nb").text(JSON.parse(localStorage.getItem('items')))
    }else{
        $(".prod_nb").text('0')
    }

    //suppression d'un item du panier
    $('.checker[type=checkbox]').click((e)=>{
       let id = e.target.attributes[0].value;
       $.get("remove_cart/"+id,(qte)=>{ 
            let nbTot = JSON.parse(localStorage.getItem("items"));
            nbTot -=qte;
            localStorage.setItem('items',JSON.stringify(nbTot));
            $('#'+id+"tr").empty();
            $(".prod_nb").text(JSON.parse(localStorage.getItem('items')))
            // $("#totalPrice")
        });
    });

});