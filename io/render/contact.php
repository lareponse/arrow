<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-header">
                <h1>Contactew nous</h1>
            </div>

            <div class="contact-grid">
                <div class="contact-form-container">
                    <?php if (isset($data['success']) && $data['success']): ?>
                        <div class="alert alert-success">
                            <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon.
                        </div>
                    <?php endif; ?>

                    <form action="/contact" method="post" class="contact-form">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="name" class="form-label">Name *</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                required>
                            <?php if (isset($data['errors']['name'])): ?>
                                <div class="form-error"><?= htmlspecialchars($data['errors']['name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required>
                            <?php if (isset($data['errors']['email'])): ?>
                                <div class="form-error"><?= htmlspecialchars($data['errors']['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label">Subject</label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                                placeholder="What is this regarding?">
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Message *</label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-control <?= isset($data['errors']['message']) ? 'is-invalid' : '' ?>"
                                rows="6"
                                placeholder="Tell us how we can help you..."
                                required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            <?php if (isset($data['errors']['message'])): ?>
                                <div class="form-error"><?= htmlspecialchars($data['errors']['message']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-large">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>