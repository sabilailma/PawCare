</main>
<footer class="pc-footer">
  <div class="pc-container">
    <p>&copy; <?=date('Y')?> PawCare • Caring for pets</p>
  </div>
</footer>
<script>
  // small helper
  function markRead(id){ fetch('/pawcare/notifications.php?mark_read='+id).then(()=>location.reload()) }
</script>
</body>
</html>

<script>
let slideIndex = 0;

function moveSlide(n) {
    const slider = document.getElementById("ts-slider");
    const cards = document.querySelectorAll(".testimonial-card");
    const cardWidth = cards[0].offsetWidth + 20; // include gap

    slideIndex += n;

    if (slideIndex < 0) slideIndex = cards.length - 1;
    if (slideIndex >= cards.length) slideIndex = 0;

    slider.style.transform = `translateX(${-slideIndex * cardWidth}px)`;
}
</script>
