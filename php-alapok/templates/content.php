<main class="container">
    <!-- lapméret váltó -->
    <div class="row">
        <form action="">
            <div class="form-group">
                <label for="size">Page size: </label>
                <select name="size" id="size" class="form-control">
                    <?php foreach($possiblePageSizes as $pagesize): ?>
                    <option <?=$size==$pagesize? "selected" : "" ?>><?=$pagesize?></option>
                    <?php endforeach ?>

                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Küldés</button>
            </div>
        </form>
    </div>
    <hr>

    <!-- Lapozósáv -->
    <?php require "pagination.php"?>

    <?php foreach($content as $picture): ?>
    <img src="<?=$picture['thumbnail']?>">
    <?php endforeach?>

    <?php require "pagination.php"?>

</main>