<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabella utenti
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID primario
            $table->string('name'); // Nome utente
            $table->string('email')->unique(); // Email unica
            $table->timestamp('email_verified_at')->nullable(); // Data verifica email (nullable)
            $table->string('password'); // Password hashata
            $table->rememberToken(); // Token per "ricordami"
            $table->timestamps(); // created_at e updated_at
        });

        // Tabella per i token di reset password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Chiave primaria = email
            $table->string('token'); // Token di reset
            $table->timestamp('created_at')->nullable(); // Data creazione
        });

        // Tabella per la gestione delle sessioni (es. per autenticazione)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID sessione (stringa)
            $table->foreignId('user_id')->nullable()->index(); // ID utente associato
            $table->string('ip_address', 45)->nullable(); // IP dell'utente
            $table->text('user_agent')->nullable(); // Browser / sistema operativo
            $table->longText('payload'); // Dati della sessione
            $table->integer('last_activity')->index(); // Timestamp ultimo utilizzo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Elimina tutte le tabelle in ordine inverso
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
