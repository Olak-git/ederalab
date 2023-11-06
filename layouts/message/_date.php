
<div class="d-flex justify-content-center align-items-center __date__ mb-2">
  <!-- <div class="col-5"><hr class=""></div> -->
  <span class="small text-secondary"><?= $date == 'aujourd\'hui'? $date : (new \DateTime($date))->format('d-m-Y'); ?></span>
  <!-- <div class="col-5"><hr class=""></div> -->
</div>