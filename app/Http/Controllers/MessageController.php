<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Registro; 
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Usuario no autenticado.',
            ], 401);
        }
        
        try {
            $request->validate([
                'receiver_email' => 'required|email|exists:registros,email', // Validar que el email del destinatario exista
                'message' => 'required|string',
            ]);

            // Obtener el ID del receptor
            $receiver = Registro::where('email', $request->receiver_email)->first();

            // Guardar el mensaje en la base de datos
            $message = Message::create([
                'sender_id' => auth()->id(), // ID del usuario autenticado
                'receiver_id' => $receiver->id,
                'message' => encrypt($request->message), // Encriptar el mensaje
            ]);

            return response()->json([
                'message' => 'Mensaje enviado exitosamente',
                'data' => $message,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al enviar el mensaje',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function readMessages($userId)
    {
        try {
            // Verificar si el usuario especificado existe
            $receiver = Registro::find($userId);
            if (!$receiver) {
                return response()->json([
                    'message' => 'Usuario receptor no encontrado.',
                ], 404);
            }

            // Obtener los mensajes entre el usuario autenticado y el usuario especificado
            $messages = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', auth()->id());
            })->get();

            // Verificar si hay mensajes
            if ($messages->isEmpty()) {
                return response()->json([
                    'message' => 'No hay mensajes entre los usuarios.',
                    'data' => []
                ], 204); // Código 204 No Content
            }

            // Desencriptar los mensajes
            $messages = $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => decrypt($message->message), // Desencriptar el mensaje
                    'created_at' => $message->created_at,
                    'updated_at' => $message->updated_at,
                ];
            });

            return response()->json([
                'message' => 'Mensajes leídos exitosamente',
                'data' => $messages,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la consulta a la base de datos',
                'error' => $e->getMessage(),
            ], 500); // Código 500 Internal Server Error
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al leer los mensajes',
                'error' => $e->getMessage(),
            ], 500); // Código 500 Internal Server Error
        }
    }

    public function loadInboxMessages()
    {
        try {
            // Obtener los mensajes recibidos por el usuario autenticado
            $messages = Message::where('receiver_id', auth()->id())->get();
    
            // Verificar si hay mensajes
            if ($messages->isEmpty()) {
                return response()->json([
                    'message' => 'No hay mensajes en el buzón.',
                    'data' => []
                ], 200); // Código 200 OK
            }
    
            // No desencriptar los mensajes todavía
            $messages = $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_email' => Registro::find($message->sender_id)->email, // Obtener el email del remitente
                    'message' => $message->message, // Mantener el mensaje encriptado
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            });
    
            return response()->json([
                'message' => 'Mensajes cargados exitosamente.',
                'data' => $messages,
            ], 200); // Código 200 OK
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar los mensajes.',
                'error' => $e->getMessage(),
            ], 500); // Código 500 Internal Server Error
        }
    }
    

    public function decrypt($id)
    {
        try {
    
            $message = Message::findOrFail($id); // Asegúrate de que la lógica aquí sea correcta
    
            // Desencriptar el mensaje
            $decryptedMessage = decrypt($message->message); // Asegúrate de que esta es la forma correcta de desencriptar
    
            return response()->json([
                'message' => 'Mensaje desencriptado exitosamente',
                'data' => [
                    'sender_email' => $message->sender_id, // Asegúrate de que esta propiedad sea correcta
                    'message' => $decryptedMessage,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al desencriptar el mensaje: " . $e->getMessage());
            return response()->json([
                'message' => 'Error al desencriptar el mensaje',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function reEncrypt(Request $request, $id)
    {
        try {
            $message = Message::findOrFail($id);
            
            // Reencriptar el mensaje
            $encryptedMessage = encrypt($request->input('message')); // Asegúrate de que esta es la forma correcta de encriptar
            
            $message->message = $encryptedMessage;
            $message->save();

            return response()->json(['data' => ['message' => $encryptedMessage]], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error reencriptando el mensaje.'], 500);
        }
    }

}
