/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */

<?php
// Pied de page commun
// À inclure en bas de chaque page protégée, AVANT </body>
 
?>

    </div><! fin .page-content>

    <! FOOTER >
    <footer class="footer">
      <div>
        &copy; <?= date('Y') ?>
        <strong style="color:var(--primary);">USTHB</strong> –
        Faculté d'Informatique – Gestion de Scolarité
      </div>
      <div style="display:flex;align-items:center;gap:12px;color:var(--text-muted);">
        <span>PHP <?= phpversion() ?></span>
        <span style="width:4px;height:4px;border-radius:50%;background:var(--border);display:inline-block;"></span>
        <span>Module PWEB 2025/2026</span>
        <span style="width:4px;height:4px;border-radius:50%;background:var(--border);display:inline-block;"></span>
        <span>Dr. LAACHEMI</span>
      </div>
	  <div>
    &copy; <?= date('Y') ?>
       <strong>Système de gestion académique</strong><br>
       Réalisé par : mechai ouiam – L2 Informatique
</div>
    </footer>

  </div><! fin .main-content >
</div><!  fin .wrapper >

<! MODAL OVERLAY   >
<div class="modal-overlay" id="globalModalOverlay" onclick="closeModal()">
  <div class="modal" id="globalModal" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3 id="globalModalTitle">Titre</h3>
      <button class="modal-close" onclick="closeModal()">
        <i class="fa fa-xmark"></i>
      </button>
    </div>
    <div class="modal-body" id="globalModalBody"></div>
    <div class="modal-footer" id="globalModalFooter">
      <button class="btn btn-outline" onclick="closeModal()">Annuler</button>
    </div>
  </div>
</div>

<!JAVASCRIPT GLOBAL >
<script>
  /* Modal */
  function openModal(title, bodyHTML, footerHTML = '') {
    document.getElementById('globalModalTitle').innerHTML = title;
    document.getElementById('globalModalBody').innerHTML  = bodyHTML;
    if (footerHTML) {
      document.getElementById('globalModalFooter').innerHTML = footerHTML;
    }
    document.getElementById('globalModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    document.getElementById('globalModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
  }

  /*  Confirmation suppression  */
  function confirmDelete(url, nom) {
     openModal(
  '<i class="fa fa-trash" style="color:#dc2626;margin-right:8px;"></i>Confirmer la suppression',
  `<p>Voulez-vous vraiment supprimer <strong>${nom}</strong> ?</p>
   <p style="color:var(--text-muted);font-size:12px;margin-top:8px;">
   Cette action est irréversible.
   </p>`,
  `<button class="btn btn-outline" onclick="closeModal()">Annuler</button>
   <a href="${url}" class="btn btn-danger">
     <i class="fa fa-trash"></i> Supprimer
   </a>`
);
  }

  /* Notifications */
  function toggleNotifications() {
    alert('pas de notification pour le moment.');
  }

  /*  Fermer les alertes  */
  document.querySelectorAll('.alert').forEach(function(alert) {
    setTimeout(function() {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  });
</script>

</body>
</html>