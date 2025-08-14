(function ($, Drupal) {
  Drupal.behaviors.searchPopupBehavior = {
    attach: function (context) {
      const searchBtn = context.querySelector('#searchBtn');
      const searchPopup = context.querySelector('#searchPopup');
      const closePopup = context.querySelector('#closePopup');

      if (!searchBtn || !searchPopup || !closePopup) return;

      // Empêcher le double-binding si Ajax
      if (searchBtn.hasPopupBound) return;
      searchBtn.hasPopupBound = true;

      searchBtn.addEventListener('click', () => {
        searchPopup.style.display = 'block';
        const input = searchPopup.querySelector('input');
        if (input) setTimeout(() => input.focus(), 50);
      });

      closePopup.addEventListener('click', () => {
        searchPopup.style.display = 'none';
      });

      window.addEventListener('click', (e) => {
        if (e.target === searchPopup) {
          searchPopup.style.display = 'none';
        }
      });
    }
  };
})(jQuery, Drupal);
