<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="card">
    <h2 class="section-title">Tambah Surat Keputusan</h2>
    <p class="section-subtitle">Pilih proposal yang sudah disetujui, lalu upload file SK.</p>

    <?php if (empty($data)): ?>
        <div class="placeholder-box">
            Belum ada proposal disetujui yang bisa dibuatkan SK.
        </div>
    <?php else: ?>
        <form action="<?= base_url('/admin/surat-keputusan/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Pilih Mahasiswa + Proposal</label>
                <select name="proposal_ta_id" class="input" required onchange="isiDataProposal(this)">
                    <option value="">-- Pilih Proposal --</option>
                    <?php foreach ($data as $row): ?>
                        <option 
                            value="<?= esc((string) $row['id']) ?>"
                            data-mahasiswa="<?= esc((string) $row['mahasiswa_id']) ?>"
                            data-judul="<?= esc((string) $row['pengajuan_judul_id']) ?>"
                        >
                            <?= esc($row['nama_mahasiswa']) ?> 
                            (<?= esc($row['nim']) ?>) - 
                            <?= esc($row['judul']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="mahasiswa_id" id="mahasiswa_id">
            <input type="hidden" name="pengajuan_judul_id" id="pengajuan_judul_id">

            <div class="form-group">
                <label>Nomor SK</label>
                <input type="text" name="nomor_sk" class="input" value="<?= old('nomor_sk') ?>" required>
            </div>

            <div class="form-group">
                <label>Tanggal Terbit</label>
                <input type="date" name="tanggal_terbit" class="input" value="<?= old('tanggal_terbit') ?>" required>
            </div>

            <div class="form-group">
                <label>Upload File SK</label>
                <input 
                    type="file" 
                    name="file_sk[]" 
                    class="input" 
                    multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                >
                <small style="color:#64748b; display:block; margin-top:8px;">
                    Bisa upload lebih dari 1 file. Format: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 5MB per file.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan SK</button>
        </form>
    <?php endif; ?>
</div>

<script>
function isiDataProposal(select) {
    const option = select.options[select.selectedIndex];

    document.getElementById('mahasiswa_id').value = option.getAttribute('data-mahasiswa') || '';
    document.getElementById('pengajuan_judul_id').value = option.getAttribute('data-judul') || '';
}
</script>

<?= $this->endSection() ?>