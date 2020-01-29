<nav>
    <ul class="pagination">

    <li class="page-item <?=$page<=1?"disabled":""?>"><a class="page-link" href="?size=<?=$size?>&page=<?=$page-1?>">Prev</a></li>
    <li class="page-item <?=$page<=2?"d-none":""?>"><a class="page-link" href="?size=<?=$size?>&page=1">1</a></li>
    <li class="page-item <?=$page<=3?"d-none":""?>"><a class="page-link" href="#">...</a></li>
    <li class="page-item <?=$page<=1?"d-none":""?>"><a class="page-link" href="?size=<?=$size?>&page=<?=$page-1?>"><?=$page-1?></a></li>

    <li class="page-item active"><a class="page-link" href="#"><?=$page?></a></li>

    <li class="page-item <?=$page>=$lastpage?"d-none":""?>"><a class="page-link" href="?size=<?=$size?>&page=<?=$page+1?>"><?=$page+1?></a></li>
    <li class="page-item <?=$page>=$lastpage - 2?"d-none":""?>"><a class="page-link" href="#">...</a></li>
    <li class="page-item <?=$page>=$lastpage - 1?"d-none":""?>"><a class="page-link" href="?size=<?=$size?>&page=<?=$lastpage?>"><?=$lastpage?></a></li>
    <li class="page-item <?=$page>=$lastpage?"disabled":""?>"><a class="page-link" href="?size=<?=$size?>&page=<?=$page+1?>">Next</a></li>

    </ul>
</nav>