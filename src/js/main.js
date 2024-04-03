if (!localStorage.getItem('originalReferrer')) {
  localStorage.setItem('originalReferrer', document.referrer);
}