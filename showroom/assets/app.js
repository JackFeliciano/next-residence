(function () {
  var input = document.getElementById('q');
  var grid = document.getElementById('grid');
  var empty = document.getElementById('empty');
  var chips = Array.prototype.slice.call(document.querySelectorAll('.chip'));
  var cards = Array.prototype.slice.call(document.querySelectorAll('.card'));
  var country = '';

  function apply() {
    var q = (input && input.value ? input.value : '').trim().toLowerCase();
    var shown = 0;
    cards.forEach(function (card) {
      var hay = (card.dataset.name + ' ' + card.dataset.city + ' ' + card.dataset.country).toLowerCase();
      var byText = q === '' || hay.indexOf(q) !== -1;
      var byCountry = country === '' || card.dataset.country === country;
      var visible = byText && byCountry;
      card.hidden = !visible;
      if (visible) { shown++; }
    });
    chips.forEach(function (chip) {
      chip.setAttribute('aria-pressed', chip.dataset.country === country ? 'true' : 'false');
    });
    if (empty) { empty.hidden = shown !== 0; }
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      country = chip.dataset.country === country ? '' : chip.dataset.country;
      apply();
    });
  });

  if (input) { input.addEventListener('input', apply); }
  apply();
}());
