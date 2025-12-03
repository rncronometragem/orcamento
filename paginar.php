<div style="text-align:center;"><nav aria-label="...">
<ul class="pagination">
        <li><a href="?pagina=1"><<</a></li>
        <li class="">
            <a href="<?php if($pagina <= 1){ echo '#'; } else { echo "?pagina=".($pagina - 1); } ?>"> < </a>
</li>
        <li class="<?php if($pagina <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pagina <= 1){ echo '#'; } else { echo "?pagina=".($pagina - 1); } ?>"><?php if($pagina <= 1){ echo '0'; } else { echo "".($pagina - 1); } ?></a>
        </li>
<li class="active">
      <span class="">
            <a  href="" class="p-3 mb-2 bg-primary text-white"><?php if($pagina <= 1){ echo '1'; } else { echo "".($pagina = $pagina); } ?><?php if($pagina){ echo ''; } else { echo "".($pagina); } ?></a>
</span></li>
<li class="<?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
            <a href="<?php if($pagina >= $total_paginas){ echo '#'; } else { echo "?pagina=".($pagina + 1); } ?>"><?php if($pagina <= 1){ echo '2'; } else { echo "".($pagina + 1); } ?></a>
        </li>
<li class="<?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
            <a href="<?php if($pagina >= $total_paginas){ echo '#'; } else { echo "?pagina=".($pagina + 1); } ?>"> > </a>
        </li>
        <li><a href="?pagina=<?php echo $total_paginas; ?>"> >></a></li>
    </ul>
</nav></div>