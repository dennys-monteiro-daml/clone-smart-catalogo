<table class="form-table">
    <tr>
        <th> <label for="fabricante">Fabricante</label></th>
        <td>
            <?php
            echo Fabricante::get_select('fabricante');
            ?>
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <button class="button">Atualizar</button>
            <!-- <a href="#upload-progress" rel="modal:open">Open Modal</a> -->
        </td>
    </tr>
</table>