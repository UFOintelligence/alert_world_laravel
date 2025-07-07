import 'package:alert_world/features/auth/data/models/user_model.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:alert_world/bloc/auth/auth_bloc.dart';
import 'package:alert_world/bloc/auth_event.dart';
import 'package:alert_world/ui/page/login_page.dart';
import 'package:alert_world/ui/page/alert_map_page.dart';
import 'package:alert_world/ui/page/alert_list_page.dart';
import '../widgets/panic_button_widget.dart';
import '../../bloc/auth/auth_bloc.dart';
import '../../bloc/auth/auth_event.dart';
import '../../bloc/auth/auth_state.dart';
class HomePage extends StatefulWidget {
  final UserModel user;
  
  const HomePage({super.key, required this.user});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  void _cerrarSesion() {
    context.read<AuthBloc>().add(LogoutRequested());
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => LoginPage()),
      (route) => false,
    );
  }

  void _onMenuSelected(String value) {
    if (value == 'logout') {
      _cerrarSesion();
    } else if (value == 'profile') {
      Navigator.pushNamed(
        context,
        '/profile',
        arguments: {'userName': widget.user.name},
      );
    }
  }

  void _onTabTapped(int index) {
    switch (index) {
      case 0:
        // Ya estás en Inicio, no navega
        break;
      case 1:
        Navigator.pushNamed(context, '/alert_map');
        break;
      case 2:
        Navigator.pushNamed(context, '/alert_list');
        break;
      case 3:
        Navigator.pushNamed(context, '/profile', arguments: {
          'userName': widget.user.name,
        });
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    final firstLetter = (widget.user.name.isNotEmpty)
        ? widget.user.name[0].toUpperCase()
        : '?';

    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: Text('Bienvenido, ${widget.user.name}'),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {
              // Navegar a notificaciones (si lo necesitas)
            },
          ),
          PopupMenuButton<String>(
            onSelected: _onMenuSelected,
            icon: CircleAvatar(
              backgroundColor: Colors.blue,
              child: Text(
                firstLetter,
                style: const TextStyle(color: Colors.white),
              ),
            ),
            itemBuilder: (context) => const [
              PopupMenuItem(
                value: 'profile',
                child: Text('Mi perfil'),
              ),
              PopupMenuItem(
                value: 'logout',
                child: Text('Cerrar sesión'),
              ),
            ],
          ),
        ],
      ),
      body: Stack(
        children: const [
          Column(
            children: [
              SizedBox(height: 8),
              Expanded(flex: 2, child: AlertMapPage()),
              Expanded(flex: 3, child: AlertList()),
            ],
          ),
          Positioned(
            bottom: 100,
            right: 20,
            child: PanicButton(),  // <-- Aquí va el botón flotante
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.pushNamed(
            context,
            '/report',
            arguments: {'userName': widget.user.name},
          );
        },
        child: const Icon(Icons.add_alert),
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 0,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        onTap: _onTabTapped,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Inicio'),
          BottomNavigationBarItem(icon: Icon(Icons.map), label: 'Mapa'),
          BottomNavigationBarItem(icon: Icon(Icons.list), label: 'Alertas'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Perfil'),
        ],
      ),
    );
  }
}

