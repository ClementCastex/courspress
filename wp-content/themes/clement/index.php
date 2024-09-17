<?php get_header();
        include_once plugin_dir_path(__FILE__) . './plugins/clement-plug/plugin.php'; 
        ?>




<ul>
    <?php while (have_posts()):
        the_post(); ?>

        <li>
            <a href="<?php the_permalink(); ?>">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text"><?php the_content(); ?></p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </a>

        </li>
        <?php 
      // function activer_plugin_filtrage_insultes() {
        $phrase = "Je ne comprends pas pourquoi tu es toujours aussi grognard et tête de mule. Franchement, c'est insupportable de te voir agir comme un imbécile à longueur de journée. Tu es vraiment stupide et fainéant, et tout le monde le voit. Sérieusement, arrête d'être un crétin et commence à te comporter comme une personne responsable. Il n'y a pas moyen de discuter avec toi sans que tu sois aussi borné et irrespectueux. C'est incroyable à quel point tu peux être un vrai malotru. On dirait que tu cherches constamment à être le plus désagréable possible, tu ne te rends même pas compte à quel point tu peux être méchant et horrible avec les autres.";
        $phraseFiltrée = filtrerInsultes($phrase);
        echo $phraseFiltrée;
      // }
      // function desactiver_plugin_filtrage_insultes() {
      //   $phrase = "Je ne comprends pas pourquoi tu es toujours aussi grognard et tête de mule. Franchement, c'est insupportable de te voir agir comme un imbécile à longueur de journée. Tu es vraiment stupide et fainéant, et tout le monde le voit. Sérieusement, arrête d'être un crétin et commence à te comporter comme une personne responsable. Il n'y a pas moyen de discuter avec toi sans que tu sois aussi borné et irrespectueux. C'est incroyable à quel point tu peux être un vrai malotru. On dirait que tu cherches constamment à être le plus désagréable possible, tu ne te rends même pas compte à quel point tu peux être méchant et horrible avec les autres.";
      //   echo $phrase;
      // }

?>
    <?php endwhile; ?>
</ul>

<?php get_footer(); ?>