<?php
include "../frontend/ownerheader.php";
include "../backend/ownerpetlist_b.php";
?>

<!-- SWEETALERT2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main">
    <div class="page-title text-center mt-4">
        <div class="title-wrapper">
            <h1>My Pet List</h1>
            <p class="text-muted">All you registered pets are displayed below.</p>
        </div>
    </div>

    <section id="petlist" class="section py-4">


        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">

                <?php if (!empty($pets)): ?>

                    <?php foreach ($pets as $pet): ?>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="pet-card shadow-lg rounded-4 p-3">


                                <div class="pet-image mb-3">
                                    <img src="../uploads/pets/<?= htmlspecialchars($pet['pet_image']); ?>"
                                        alt="<?= htmlspecialchars($pet['pet_name']); ?>" class="img-fluid rounded"
                                        style="height:220px; width:100%; object-fit:cover;">
                                </div>


                                <div class="pet-info text-center">
                                    <h5 class="fw-bold"><?= htmlspecialchars($pet['pet_name']); ?></h5>
                                    <br>

                                    <p class="mb-1">
                                        <?= htmlspecialchars($pet['species']); ?>: <?= htmlspecialchars($pet['breed']); ?>
                                    </p>

                                    <p class="mb-1">Gender: <?= htmlspecialchars($pet['gender']); ?></p>
                                    <p class="mb-1">Color: <?= htmlspecialchars($pet['color']); ?></p>

                                    <?php $formattedDOB = date("d/m/Y", strtotime($pet['dob'])); ?>
                                    <p class="mb-2">DOB: <?= $formattedDOB; ?></p>

                                    <br>
                                    <a href="pet-update.php?id=<?= $pet['pet_id']; ?>" class="col-12 mt-4 text-center">
                                        <button type="edit" class="btn btn-warning px-5">Edit</button>
                                    </a>
                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="col-12 text-center">
                        <p class="text-muted">
                            Please <a href="../frontend/newpet.php" class="text-primary fw-bold">REGISTER</a> Your Pet First
                        </p>
                    </div>


                <?php endif; ?>

            </div>
        </div>
    </section>
</main>



<?php
include "../frontend/footer.php";
?>