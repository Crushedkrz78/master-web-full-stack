import { ModuleWithProviders } from "@angular/core";
import { Routes, RouterModule } from '@angular/router';

// Import de componentes
import { LoginComponent } from "./components/login/login.component";
import { RegisterComponent } from "./components/register/register.component";

// Definicion de rutas
const appRoutes: Routes = [
  {path: '', component: LoginComponent},
  {path: 'inicio', component: LoginComponent},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent}
];

// Export configuracion de rutas
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
