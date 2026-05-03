# Librerias
from telegram import Update
from telegram.ext import ApplicationBuilder, ContextTypes, CommandHandler, MessageHandler, filters

# Funcion que responde a /start
async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await context.bot.send_message(
        chat_id=update.effective_chat.id, 
        text="¡Hola! Soy el bot de Riki. Envíame un mensaje y lo repetiré."
    )

# Tomar texto del usuario y devolverlo
async def echo(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await context.bot.send_message(
        chat_id=update.effective_chat.id, 
        text=update.message.text
    )

if __name__ == '__main__':
    app = ApplicationBuilder().token('poner codigo token telegram aqui!!!').build() # Poner toker que nos da el bot father
    
    # Registrar comando /start
    app.add_handler(CommandHandler('start', start))
    
    # Registrar un manejador para cualquier mensaje de texto (eco)
    app.add_handler(MessageHandler(filters.TEXT & (~filters.COMMAND), echo))
    
    print("El bot está activo... 'Ctrl+C' para detenerlo.")
    app.run_polling()