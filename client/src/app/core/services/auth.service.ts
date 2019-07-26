import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http'
import { tap } from 'rxjs/operators';
import { Router } from '@angular/router';

interface myData {
  status: boolean,
  dados: any,
  message: string,
  userID: string,
  result: string;
  token: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  public urlApi: string = 'http://localhost:3000/painel-perfil/backend/php/api';

  constructor(private http: HttpClient, private router: Router) { }

  public get token() {
    return localStorage.getItem('userLogged');
  }

  /*public get isLoggedIn(): Observable<isLoggedIn> {
    return this.http.get<isLoggedIn>(`${this.urlApi}/auth/verify`)
  }*/


  public get isAuthenticated() {
    return !!localStorage.getItem('userLogged');
  }

  public get isLoggedIn(): boolean {
    return (localStorage.getItem('userLogged') !== null);
  }

  loginUser(email: string, senha: string) {
    const headers = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Cache-Control': 'no-cache' })
    };

    const dadosLogin = {
      email: email,
      senha: senha
    }

    return this.http.post<myData>(`${this.urlApi}/auth`, dadosLogin, headers);
  }

  onLogout() {
    localStorage.removeItem('userLogged');
    this.router.navigateByUrl('/');
  }

}
