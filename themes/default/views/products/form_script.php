<?php ?>

<script>
    function variant_add(id) {
        var ref = String(Math.random()).replace('.', '_');
        if (id) {
            ref = id;
        } else {
            id = 0;
        }
        var tpl = '<tr id="var-' + ref + '">\n\
        <td>\n\
            <input type="hidden" name="variants[id][]" value="' + id + '">\n\
            <select name="variants[size][]" class="form-control select2 tip not-validate" id="size-' + ref + '">\n\
                <option value="">Selecione</option>\n\
                <option>P</option>\n\
                <option>M</option>\n\
                <option>G</option>\n\
                <option>GG</option>\n\
                <option>GGG</option>\n\
                <option>G1</option>\n\
                <option>G2</option>\n\
                <option>G3</option>\n\
                <option>G4</option>\n\
            </select>\n\
        </td>\n\
        <td>\n\
            <select name="variants[color][]" class="form-control select2 tip not-validate" id="color-' + ref + '">\n\
                <option value="">Selecione</option>\n\
                <option>Amarelo</option>\n\
                <option>Azul e Branco</option>\n\
                <option>Azul e Rosa</option>\n\
                <option>Azul</option>\n\
                <option>Azul Celeste</option>\n\
                <option>Azul Celeste Escuro</option>\n\
                <option>Azul Marinho</option>\n\
                <option>Azul e Rosa</option>\n\
                <option>Bege Escuro</option>\n\
                <option>Bege</option>\n\
                <option>Bege Amarelado</option>\n\
                <option>Branco Preto e Vermelho</option>\n\
                <option>Branco e Rosa</option>\n\
                <option>Branco</option>\n\
                <option>Caqui</option>\n\
                <option>Coral</option>\n\
                <option>Cinza</option>\n\
                <option>Dourado</option>\n\
                <option>Laranja Escuro</option>\n\
                <option>Lilás</option>\n\
                <option>Marrom</option>\n\
                <option>Marrom e Verde</option>\n\
                <option>Marsala e Bege</option>\n\
                <option>Marsala e Rosa</option>\n\
                <option>Marsala</option>\n\
                <option>Mostarda</option>\n\
                <option>Prata</option>\n\
                <option>Preto e Bege</option>\n\
                <option>Preto e Branco</option>\n\
                <option>Preto e Rosa</option>\n\
                <option>Preto</option>\n\
                <option>Rosa</option>\n\
                <option>Rosa e Branco</option>\n\
                <option>Rosé</option>\n\
                <option>Rosé e Bege</option>\n\
                <option>Rosé e Preto</option>\n\
                <option>Salmão</option>\n\
                <option>Telha</option>\n\
                <option>Terra</option>\n\
                <option>Terracota</option>\n\
                <option>Verde Água-Marinha</option>\n\
                <option>Verde Aquamarine Claro</option>\n\
                <option>Verde Claro</option>\n\
                <option>Verde e Branco</option>\n\
                <option>Verde e Rosa</option>\n\
                <option>Verde e Preto</option>\n\
                <option>Verde Escuro</option>\n\
                <option>Verde Musgo</option>\n\
                <option>Vermelho</option>\n\
                <option>Vermelho e Branco</option>\n\
                <option>Vermelho e Preto</option>\n\
            </select>\n\
        </td>\n\
        <td>\n\
            <div class="input-group">\n\
                <input type="number" class="form-control not-validate" name="variants[quantity][]" id="quantity-' + ref + '">\n\
                <div class="input-group-addon">\n\
                    <a href="#" onclick="variant_del(\'var-' + ref + '\')" title="Excluir"><i class="fa fa-trash-o"></i></a>\n\
                </div>\n\
           </div>\n\
        </td>\n\
        </tr>';
                
        $('#varTable tbody').append(tpl);

        $('#varTable .select2').select2();
    }
    
    function variant_del(ref) {
        $("#varTable input, #varTable select").removeAttr('required');
        
        $('#'+ref).remove();
    }
</script>