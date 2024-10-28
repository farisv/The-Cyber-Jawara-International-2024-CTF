package com.cyberjawara.chall.web.javabox.controller;

import com.cyberjawara.chall.web.javabox.util.JwtUtil;

import io.jsonwebtoken.Claims;

import jakarta.servlet.http.Cookie;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.Objects;

import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

@Controller
public class MainController {

  @GetMapping("/")
  public String index() {
    return "index";
  }

  @GetMapping("/register")
  public String registerPage() {
    return "register";
  }

  @PostMapping("/register")
  public String register(@RequestParam String username, @RequestParam String password,
                      HttpServletResponse response) {
    if (username != null && password != null && username.length() > 3 && password.length() > 3) {
      String jwt = JwtUtil.generateToken(username, false);
      Cookie cookie = new Cookie("jwt", jwt);
      cookie.setHttpOnly(true);
      cookie.setPath("/");
      response.addCookie(cookie);
      return "redirect:/dashboard";
    }
    return "redirect:/register";
  }

  @GetMapping("/dashboard")
  public String dashboard(@CookieValue(value = "jwt", defaultValue = "") String jwt,
                           org.springframework.ui.Model model) {
    try {
      Claims claims = JwtUtil.validateToken(jwt);
      String username = claims.get("username", String.class);
      Boolean isAdmin = claims.get("isAdmin", Boolean.class);
      if (isAdmin) {
        String filePath = "/flag.txt";
        try (BufferedReader br = new BufferedReader(new FileReader(filePath))) {
          String content = br.readLine();
          model.addAttribute("flag", content);
        } catch (IOException e) {
          // pass
        }
      }
      model.addAttribute("username", username);
      model.addAttribute("isAdmin", isAdmin);
      return "dashboard";
    } catch (Exception e) {
      return "redirect:/register";
    }
  }

  @GetMapping("/logout")
  public String logout(HttpServletResponse response) {
    Cookie jwtCookie = new Cookie("jwt", "");
    jwtCookie.setMaxAge(0); 
    jwtCookie.setPath("/");
    jwtCookie.setHttpOnly(true);
    response.addCookie(jwtCookie);
    return "redirect:/";
  }

  @GetMapping("/assets/**")
  public ResponseEntity<byte[]> getAssetFile(HttpServletRequest request) throws Exception {
    String requestURI = request.getRequestURI();
    String resourcePath = "/assets/" + requestURI.substring("/assets/".length());

    try (InputStream inputStream = getClass().getResourceAsStream(resourcePath)) {
      if (inputStream == null || !hasExtension(resourcePath)) {
        throw new Exception("getResourceAsStream failed");
      }

      byte[] fileContent = inputStream.readAllBytes();
      String mimeType = Files.probeContentType(Path.of(resourcePath));
      if (mimeType == null) {
        if (resourcePath.endsWith(".css")) {
          mimeType = "text/css";
        } else {
          mimeType = "text/plain";
        }
      }

      HttpHeaders headers = new HttpHeaders();
      headers.setContentType(MediaType.parseMediaType(mimeType));

      return new ResponseEntity<>(fileContent, headers, HttpStatus.OK);
    } catch (IOException e) {
      return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(null);
    }
  }

  public static boolean hasExtension(String filename) {
    if (filename == null || filename.isEmpty()) {
      return false;
    }

    if (filename.length() < 3) {
      return false;
    }

    int dotIndex = filename.lastIndexOf('.');
    return dotIndex > 0 && dotIndex < filename.length() - 2;
  }
}